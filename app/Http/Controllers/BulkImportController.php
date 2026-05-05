<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BulkImportController extends Controller
{
    /**
     * Show the bulk import form
     */
    public function showImportForm()
    {
        return view('import.bulk-import');
    }

    /**
     * Validate and preview CSV data before import
     */
    public function previewImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120', // 5MB max
        ]);

        $file = $request->file('csv_file');
        $currentUser = Auth::user();
        $isAdmin = $currentUser->role->name === 'Admin';
        $companyId = $request->input('company_id');
        
        if (!$isAdmin) {
            $companyId = $currentUser->company_id;
        }

        $rows = $this->parseCSV($file);
        
        if (empty($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'CSV file is empty or no valid data found.',
                'valid_rows' => [],
                'error_rows' => [],
            ], 400);
        }

        $validRows = [];
        $errorRows = [];
        $seenEmails = []; // Track emails within this import

        foreach ($rows as $rowIndex => $row) {
            $lineNumber = $rowIndex + 2; // +2 because row 1 is headers, 0-indexed
            $errors = [];

            // Validate required fields
            $name = trim($row[0] ?? '');
            $email = trim(strtolower($row[1] ?? ''));
            $phone = trim($row[2] ?? '');
            $deptName = trim($row[3] ?? '');
            $joinDate = trim($row[4] ?? '');

            // Check required fields
            if (empty($name)) {
                $errors[] = 'Name is required';
            }
            if (empty($email)) {
                $errors[] = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Invalid email format';
            }

            // Check for duplicate email within this import
            if (!empty($email)) {
                if (in_array($email, $seenEmails)) {
                    $errors[] = 'Email appears multiple times in this import';
                } else {
                    $seenEmails[] = $email;
                }
            }

            // Check if email already exists in same company
            if (!empty($email)) {
                $existingUser = User::where('email', $email)
                    ->where('company_id', $companyId)
                    ->first();
                if ($existingUser) {
                    $errors[] = 'Email already exists in this company (will be skipped)';
                }
            }

            // Validate phone format if provided (7-15 digits)
            if (!empty($phone) && !preg_match('/^\d{7,15}$/', $phone)) {
                $errors[] = 'Phone must be 7-15 digits';
            }

            // Validate department exists in this company
            if (!empty($deptName)) {
                $department = Department::where('company_id', $companyId)
                    ->where('name', $deptName)
                    ->first();
                if (!$department) {
                    $errors[] = "Department '{$deptName}' not found in selected company";
                }
            }

            // Validate join date format if provided
            if (!empty($joinDate)) {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $joinDate)) {
                    $errors[] = 'Join date must be in format YYYY-MM-DD';
                } else {
                    $date = \DateTime::createFromFormat('Y-m-d', $joinDate);
                    if (!$date) {
                        $errors[] = 'Invalid join date';
                    }
                }
            }

            if (empty($errors)) {
                $validRows[] = [
                    'name' => $name,
                    'email' => $email,
                    'phone_number' => $phone ?: null,
                    'department_name' => $deptName ?: null,
                    'join_date' => $joinDate ?: null,
                    'company_id' => $companyId,
                ];
            } else {
                $errorRows[] = [
                    'line' => $lineNumber,
                    'data' => [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'department' => $deptName,
                        'join_date' => $joinDate,
                    ],
                    'errors' => $errors,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Preview ready: " . count($validRows) . " valid rows, " . count($errorRows) . " errors",
            'valid_count' => count($validRows),
            'error_count' => count($errorRows),
            'valid_rows' => $validRows,
            'error_rows' => $errorRows,
        ]);
    }

    /**
     * Perform the actual bulk import
     */
    public function confirmImport(Request $request)
    {
        $request->validate([
            'valid_rows' => 'required|array',
            'valid_rows.*.name' => 'required|string|max:255',
            'valid_rows.*.email' => 'required|email',
            'valid_rows.*.company_id' => 'required|exists:companies,id',
        ]);

        $validRows = $request->input('valid_rows');
        $employeeRole = Role::where('name', 'Employee')->first();
        
        if (!$employeeRole) {
            return response()->json([
                'success' => false,
                'message' => 'Employee role not found in system',
            ], 500);
        }

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($validRows as $index => $row) {
            try {
                // Final check: email shouldn't exist in same company
                $emailExists = User::where('email', $row['email'])
                    ->where('company_id', $row['company_id'])
                    ->exists();

                if ($emailExists) {
                    $skipped++;
                    continue;
                }

                $departmentId = null;
                if (!empty($row['department_name'])) {
                    $department = Department::where('company_id', $row['company_id'])
                        ->where('name', $row['department_name'])
                        ->first();
                    if ($department) {
                        $departmentId = $department->id;
                    }
                }

                // Determine password: use phone if provided, else default
                $password = $row['phone_number'] ?? 'password123';

                User::create([
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone_number' => $row['phone_number'],
                    'password' => Hash::make($password),
                    'role_id' => $employeeRole->id,
                    'company_id' => $row['company_id'],
                    'department_id' => $departmentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (!empty($row['join_date'])) {
                    $user = User::where('email', $row['email'])->first();
                    $user->update(['created_at' => $row['join_date']]);
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$index}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Import complete: {$imported} imported, {$skipped} skipped",
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ]);
    }

    /**
     * Parse CSV file and return rows
     */
    private function parseCSV($file)
    {
        $rows = [];
        $handle = fopen($file->getPathname(), 'r');

        if (!$handle) {
            return $rows;
        }

        // Skip header row
        fgetcsv($handle);

        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (!array_filter($row)) {
                continue;
            }
            $rows[] = $row;
        }

        fclose($handle);
        return $rows;
    }
}

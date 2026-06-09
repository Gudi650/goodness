<?php

namespace App\Http\Controllers;

use App\Models\CreateLiability;
use Illuminate\Http\Request;

class CreateLiabilityController extends Controller
{
    //function to store the liability
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'nullable|exists:liability_categories,id',
            'type' => 'required|string|max:255',
            'term' => 'required|in:Short-term,Long-term',
            'original_amount' => 'nullable|numeric',
            'current_amount' => 'nullable|numeric',
            'creditor' => 'nullable|string|max:255',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Active,Settled,Defaulted,Written Off'
        ]);

        $validatedData['code'] = $this->generateUniqueCode();

        $liability = CreateLiability::create($validatedData);



        return redirect()->back()->with('success', 'Liability created successfully');
    }

    /**
     * function to generate the asset code
     * The code is generated based on the current timestamp and a random string to ensure uniqueness.
     * then test to see if already exists in the database, if it does, generate a new code until a unique one is found.
     * The format of the code is LIAB- followed by a unique identifier.
     */
    protected function generateUniqueCode()
    {
        do {
            $code = 'LIAB-' . strtoupper(uniqid());
        } while (CreateLiability::where('code', $code)->exists());

        return $code;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'company' => 'required|exists:companies,id',
            'status' => 'nullable|string|max:50',
            'stock' => 'nullable|integer',
            'unit_of_measure' => 'nullable|string|max:50',
            'reorder_level' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'last_restocked_date' => 'nullable|date',
            'last_stock_movement' => 'nullable|string|max:255',
            'cost_per_unit' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'tax_vat' => 'nullable|string|max:50',
            'tax_vat_custom' => 'nullable|numeric',
            'supplier_id' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'product_description' => 'nullable|string',
            'product_image' => 'nullable|image|max:5120',
        ]);

        // Resolve company: accept either id or name and convert them into an id for storage
        $companyId = null;
        if (!empty($request->input('company'))) {
            $companyVal = $request->input('company');
            if (is_numeric($companyVal)) {
                $companyId = (int)$companyVal;
            } else {
                $company = Company::where('name', $companyVal)->first();
                if ($company) $companyId = $company->id;
            }
        }

        // SKU generation if requested or if SKU blank
        $sku = $request->input('sku');

        if ($request->has('sku_auto_generate') || empty($sku)) {
            $category = $request->input('category') ?: '';
            $name     = $request->input('name') ?: '';

            // Take first 3 letters of category
            $categoryPart = substr($category, 0, 3);

            // Slugify category part and name
            $prefix = $this->slugifySkuPart($categoryPart) . '-' . $this->slugifySkuPart($name);

            // Clean up stray hyphens
            $prefix = trim($prefix, '-');

            // Convert to uppercase
            $base = strtoupper($prefix);

            try {
                // Count existing SKUs starting with this base
                $count = Product::where('sku', 'like', $base . '%')->count();
            } catch (Exception $e) {
                // If products table doesn't exist yet, fall back safely
                $count = 0;
            }

            // Pad the number with leading zeros (e.g., 001, 002, …)
            $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

            // Final SKU
            $sku = $base ? $base . '-' . $number : 'SKU-' . time();
        }

        // Barcode generation if requested
        $barcode = $request->input('barcode');
        if ($request->has('barcode_auto_generate') || empty($barcode)) {
            $barcode = 'BAR-' . substr((string)time(), -8);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        $cost = $request->input('cost_per_unit') ?? 0;
        $sell = $request->input('selling_price') ?? 0;
        $profit = null;
        if ($cost > 0) {
            $profit = (($sell - $cost) / $cost) * 100;
        }

        $product = Product::create([
            'name' => $request->input('name'),
            'sku' => $sku,
            'barcode' => $barcode,
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'company_id' => $companyId,
            'status' => $request->input('status') ?: 'Active',
            'stock' => $request->input('stock') ?: 0,
            'unit_of_measure' => $request->input('unit_of_measure'),
            'reorder_level' => $request->input('reorder_level') ?: 0,
            'location' => $request->input('location'),
            'last_restocked_date' => $request->input('last_restocked_date'),
            'last_stock_movement' => $request->input('last_stock_movement'),
            'cost_per_unit' => $cost,
            'selling_price' => $sell,
            'tax_vat' => $request->input('tax_vat'),
            'tax_vat_custom' => $request->input('tax_vat_custom'),
            'profit_margin' => $profit,
            'supplier_id' => $request->input('supplier_id'),
            'expiry_date' => $request->input('expiry_date'),
            'product_description' => $request->input('product_description'),
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Product created successfully');
    }

    protected function slugifySkuPart($value)
    {
        return preg_replace('/[^A-Z0-9]+/', '-', strtoupper(trim($value ?? '')));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'company' => 'required|exists:companies,id',
            'status' => 'nullable|string|max:50',
            'stock' => 'nullable|integer',
            'unit_of_measure' => 'nullable|string|max:50',
            'reorder_level' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'last_restocked_date' => 'nullable|date',
            'last_stock_movement' => 'nullable|string|max:255',
            'cost_per_unit' => 'nullable|numeric',
            'selling_price' => 'nullable|numeric',
            'tax_vat' => 'nullable|string|max:50',
            'tax_vat_custom' => 'nullable|numeric',
            'supplier_id' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'product_description' => 'nullable|string',
        ]);

        // Resolve company
        $companyId = null;
        if (!empty($request->input('company'))) {
            $companyVal = $request->input('company');
            if (is_numeric($companyVal)) {
                $companyId = (int)$companyVal;
            } else {
                $company = Company::where('name', $companyVal)->first();
                if ($company) $companyId = $company->id;
            }
        }

        // SKU handling
        $sku = $request->input('sku');
        if ($request->has('sku_auto_generate') || empty($sku)) {
            $category = $request->input('category') ?: '';
            $name     = $request->input('name') ?: '';

            $categoryPart = substr($category, 0, 3);
            $prefix = $this->slugifySkuPart($categoryPart) . '-' . $this->slugifySkuPart($name);
            $prefix = trim($prefix, '-');
            $base = strtoupper($prefix);

            try {
                $count = Product::where('sku', 'like', $base . '%')->count();
            } catch (\Exception $e) {
                $count = 0;
            }

            $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
            $sku = $base ? $base . '-' . $number : 'SKU-' . time();
        }

        // Barcode handling
        $barcode = $request->input('barcode');
        if ($request->has('barcode_auto_generate') || empty($barcode)) {
            $barcode = 'BAR-' . substr((string)time(), -8);
        }

        $cost = $request->input('cost_per_unit') ?? 0;
        $sell = $request->input('selling_price') ?? 0;
        $profit = null;
        if ($cost > 0) {
            $profit = (($sell - $cost) / $cost) * 100;
        }

        $product->name = $request->input('name');
        $product->sku = $sku;
        $product->barcode = $barcode;
        $product->category = $request->input('category');
        $product->brand = $request->input('brand');
        $product->company_id = $companyId;
        $product->status = $request->input('status') ?: 'Active';
        $product->stock = $request->input('stock') ?: 0;
        $product->unit_of_measure = $request->input('unit_of_measure');
        $product->reorder_level = $request->input('reorder_level') ?: 0;
        $product->location = $request->input('location');
        $product->last_restocked_date = $request->input('last_restocked_date');
        $product->last_stock_movement = $request->input('last_stock_movement');
        $product->cost_per_unit = $cost;
        $product->selling_price = $sell;
        $product->tax_vat = $request->input('tax_vat');
        $product->tax_vat_custom = $request->input('tax_vat_custom');
        $product->profit_margin = $profit;
        $product->supplier_id = $request->input('supplier_id');
        $product->expiry_date = $request->input('expiry_date');
        $product->product_description = $request->input('product_description');

        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted');
    }

    public function show(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'stock' => $product->stock ?? 0,
            'selling_price' => $product->selling_price ?? 0,
            'unit_of_measure' => $product->unit_of_measure ?? 'Piece',
        ]);
    }
}
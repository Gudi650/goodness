<?php

namespace App\Http\Controllers;

use App\Models\CreateAssets;
use Illuminate\Http\Request;

class CreateAssetsController extends Controller
{
    //function to store the asset
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'nullable|exists:assets_categories,id',
            'type' => 'required',
            'term' => 'required|in:Short-term,Long-term',
            'original_value' => 'nullable|numeric',
            'current_value' => 'nullable|numeric',
            'depreciation_value' => 'nullable|numeric',
            'acquired' => 'nullable|date',
            'status' => 'required|in:Active,Disposed,Sold,Written Off',
        ]);

        //get the generated asset code
        $validatedData['code'] = $this->generateAssetCode();

        //dump the validated datas
        //dd($validatedData);


        $asset = CreateAssets::create($validatedData);

        return redirect()->back()->with('success', 'Asset created successfully');

    }



    /**
     * function to generate the asset code
     * The code is generated based on the current timestamp and a random string to ensure uniqueness.
     * then test to see if already exists in the database, if it does, generate a new one until we get a unique code.
     */
    protected function generateAssetCode()
    {
        do {
            $code = 'ASSET-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6)) . '-' . time();
        } while (CreateAssets::where('code', $code)->exists());

        return $code;
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\CreateAssets;
use Illuminate\Http\Request;

class FAR extends Controller
{
    //function to display the FAR page
    public function index()
    {

        $fixedAssets = $this->getFixedAssets();

        //dd($fixedAssets);

        return view('far', compact('fixedAssets'));
    }


    //get the fixed assets only from the create_assets table and display them in the FAR page
    public function getFixedAssets()
    {
        $fixedAssets = CreateAssets::where('type', 'Fixed Asset')->get();
        return $fixedAssets;
    }

    //calculating the depreciation value and current value of the fixed asset of the selected asset and display them in the FAR page
        public function calculateDepreciation($assetId)
        {
            $asset = CreateAssets::find($assetId);
    
            if (!$asset) {
                return redirect()->back()->with('error', 'Asset not found');
            }

            /**
             * check the acquisition date and the current dat
             */
    
            
        }


}

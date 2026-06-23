<?php

namespace App\Http\Controllers;

use App\Models\CreateAssets;
use App\Services\AccessControlService;
use App\Services\FAR\CalculateCurrentFar;
use Illuminate\Support\Facades\Auth;

class FAR extends Controller
{
    // function to display the FAR page
    public function index()
    {
        $currentUser = Auth::user();

        //restrict access to none qualified users here and if not qualified redirect to dashboard with error message
        if (! app(AccessControlService::class)->isCeoOrAdminOrAccountant($currentUser) && ! app(AccessControlService::class)->isManager($currentUser)) {
            return redirect()->route('dashboard')->with('error', 'You do not have access to the HRM page.');
        }

        //get the fixed asstes data from the services class and pass it to the FAR page
        $fixedAssets = app(CalculateCurrentFar::class)->calculateDepreciation();
        //dd($fixedAssets);

        return view('far', compact('fixedAssets'));
    }

    // get the fixed assets only from the create_assets table and display them in the FAR page
    public function getFixedAssets()
    {
        $fixedAssets = CreateAssets::where('type', 'Fixed Asset')->get();

        return $fixedAssets;
    }

    // calculating the depreciation value and current value of the fixed asset of the selected asset and display them in the FAR page
    public function calculateDepreciation($assetId)
    {
        $asset = CreateAssets::find($assetId);

        if (! $asset) {
            return redirect()->back()->with('error', 'Asset not found');
        }

        /**
         * check the acquisition date and the current dat
         */
    }


    //function 



}

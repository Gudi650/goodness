<?php

namespace App\Http\Controllers;

use App\Models\DividendDistribution;
use App\Models\Dividends;
use App\Models\EquityDistribution;
use Illuminate\Http\Request;

class DividendsController extends Controller
{
    /*function to get the datas needed for dividends calculations
    public function getDividendsData(Request $request)
    {

        //dd($request->all()); // Debugging line to inspect the request data

       //validate the input fields as well here
       $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'amount' => 'required|numeric|min:0',
            'declared_at' => 'required|date',
            //'paid_at' => 'nullable|date|after_or_equal:declared_at',
            //'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        //dd($validatedData); // Debugging line to inspect the validated data

        //call the function to get all the stake holders of a company and their shares and ownership percentage from the equity table
        $stakeholders = $this->getStakeholders($validatedData);

        //call the function to calculate the dividend distribution for each stakeholder based on their shares and ownership percentage
        $stakeholdersWithDividends = $this->calculateDividendDistribution($validatedData);

        dd('Stakeholders with Dividends: ' . json_encode($stakeholdersWithDividends)); // Debugging line to inspect the stakeholders with calculated dividend amounts

        
    } */

    //function to get all the stake holders of a company and their shares and ownership percentage from the equity table
    //get the datas basing on the input field the user submitted in the modal form
    public function getStakeholders($validatedData)
    {

        //dd('Called: ' . json_encode($validatedData)); // Debugging line to inspect the validated data

        // Fetch stakeholders and their shares/ownership percentages
        $stakeholders = EquityDistribution::where('company_id', $validatedData['company_id'])->get();

        //dd('Stakeholders: ' . json_encode($stakeholders)); // Debugging line to inspect the stakeholders

        return $stakeholders;
    }

    //now we can create a function to calculate the dividend distribution for each stakeholder based on their shares and ownership percentage
    public function calculateDividendDistribution($validatedData)
    {

        //get stakeholders and their shares and ownership percentage
        $stakeholders = $this->getStakeholders($validatedData);

        //dd($stakeholders); // Debugging line to inspect the stakeholders

        // Calculate the dividends
        foreach ($stakeholders as $stakeholder) {
            $stakeholder->dividend_amount = ($validatedData['amount'] * $stakeholder->ownership_percentage) / 100;
        }

        //dd($stakeholders); // Debugging line to inspect the stakeholders with calculated dividend amounts

        return $stakeholders;
    
    }

    //function to store the dividend datas and the dividend distribution datas in the db
    public function store(Request $request)
    {
        //validate the input fields
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'amount' => 'required|numeric|min:0',
            'declared_at' => 'required|date',
            //'paid_at' => 'nullable|date|after_or_equal:declared_at',
            //'status' => 'required|in:pending,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        //create a new dividend record in the db
        $dividend = Dividends::create($validatedData);

        //get the stakeholders and their shares and ownership percentage
        $stakeholdersWithDividends = $this->calculateDividendDistribution($validatedData);

        //dd( $stakeholdersWithDividends); // Debugging line to inspect the stakeholders with calculated dividend amounts

        //store the dividend distribution datas in the db
        foreach ($stakeholdersWithDividends as $stakeholder) {
            $record = DividendDistribution::create([
                'dividend_id' => $dividend->id,
                'equity_id' => $stakeholder->id,
                'shareholder_name' => $stakeholder->shareholder,
                'shares' => $stakeholder->shares,
                'ownership_percentage' => $stakeholder->ownership_percentage,
                'amount' => $stakeholder->dividend_amount,
                //'notes' => $request->input('notes'),
            ]);

            //dd($record); // Debugging line to inspect the created DividendDistribution record
        }

        return redirect()->back()->with('success', 'Dividend and distribution records created successfully.');
    }




}

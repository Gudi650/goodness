<?php

namespace Database\Seeders;

use App\Models\IncomeCategory;
use App\Models\IncomeItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IncomeItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $categories = [

            'Sales ' => [
                'Equipment Sales',
                'Spare Parts Sales',
                'Product Sales',
                'Consumables Sales',
                'Accessories Sales',
            ],

            'Service ' => [
                'Installation Services',
                'Repair Services',
                'Maintenance Services',
                'Preventive Maintenance Services',
                'Calibration Services',
                'Inspection Services',
                'Consultancy Services',
                'Training Services',
                'Technical Support Services',
                'Commission Income',
            ],

            'Rental ' => [
                'Equipment Rental Income',
                'Vehicle Rental Income',
                'Facility Rental Income',
                'Storage Rental Income',
            ],

            'Financial ' => [
                'Interest Income',
                'Dividend Income',
                'Foreign Exchange Gain',
                'Investment Income',
            ],

            'Others' => [
                'Gain on Sale of Assets',
                'Insurance Claim Income',
                'Government Grant Income',
                'Donations Received',
                'Miscellaneous Income',
            ],
        ];

        foreach ($categories as $categoryName => $items) {

            $category = IncomeCategory::firstOrCreate([
                'category_name' => $categoryName,
            ]);

            foreach ($items as $item) {

                IncomeItem::firstOrCreate([
                    'item_name' => $item,
                    'category_id' => $category->id,
                ], [
                    'description' => null,
                ]);
            }
        }


    }
}

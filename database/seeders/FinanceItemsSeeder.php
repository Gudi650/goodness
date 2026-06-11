<?php

namespace Database\Seeders;

use App\Models\FinanceItems;
use App\Models\ItemsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $categories = [

        'Cost of Goods Sold (COGS)' => [
            'Equipment Purchases',
            'Spare Parts Purchases',
            'Direct Labor (Technicians)',
            'Installation Materials',
            'Service Consumables',
            'Calibration Materials',
            'Freight Inward',
            'Subcontractor Costs',
            'Inventory Adjustments',
        ],

        'Operating Expenses' => [
            'Office Rent',
            'Utilities (Water, Electricity)',
            'Internet',
            'Telephone',
            'Office Supplies & Stationery',
            'Software Subscriptions',
            'Bank Charges',
            'Professional Fees',
            'Audit Fees',
            'Legal Fees',
            'Salaries and Wages',
            'Overtime',
            'Staff Allowances',
            'Pension Contributions',
            'Medical Insurance',
            'Staff Training',
            'Recruitment Expenses',
            'Advertising',
            'Marketing Campaigns',
            'Website Expenses',
            'Social Media Promotion',
            'Sales Commissions',
            'Business Development Expenses',
            'Fuel',
            'Vehicle Maintenance',
            'Vehicle Insurance',
            'Travel Expenses',
            'Accommodation',
            'Transport Allowances',
            'Equipment Maintenance',
            'Office Maintenance',
            'Vehicle Repairs',
            'Computer Repairs',
            'Depreciation - Vehicles',
            'Depreciation - Equipment',
            'Depreciation - Furniture',
            'Amortization',
        ],

        'Financial Expenses' => [
            'Interest Expense',
            'Loan Charges',
            'Foreign Exchange Losses',
        ],

        'Other Expenses' => [
            'Bad Debts',
            'Donations',
            'Penalties and Fines',
            'Miscellaneous Expenses',
        ]
    ];

        foreach ($categories as $categoryName => $items) {

            $category = ItemsCategory::firstOrCreate([
                'category_name' => $categoryName
            ]);

            foreach ($items as $item) {

                FinanceItems::firstOrCreate([
                    'item_name' => $item,
                    'category_id' => $category->id,
                ], [
                    'description' => null,
                ]);
            }
            
        }

    }
}

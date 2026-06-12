<?php

namespace Database\Seeders;

use App\Models\LiabilityCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LiabilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [

            [
                'category' => 'Accounts Payable',
                'description' => 'Amounts owed to suppliers and vendors.'
            ],

            [
                'category' => 'Loans & Borrowings',
                'description' => 'Bank loans, overdrafts and other borrowings.'
            ],

            [
                'category' => 'Tax Liabilities',
                'description' => 'VAT, PAYE, income tax and other tax obligations.'
            ],

            [
                'category' => 'Accrued Expenses',
                'description' => 'Expenses incurred but not yet paid.'
            ],

            [
                'category' => 'Employee Liabilities',
                'description' => 'Salaries, wages, pensions and employee benefits payable.'
            ],

            [
                'category' => 'Lease Obligations',
                'description' => 'Liabilities arising from lease agreements.'
            ],

            [
                'category' => 'Interest Payable',
                'description' => 'Interest accrued but not yet paid.'
            ],

            [
                'category' => 'Customer Deposits',
                'description' => 'Amounts received from customers before goods or services are delivered.'
            ],

            [
                'category' => 'Deferred Revenue',
                'description' => 'Revenue received in advance but not yet earned.'
            ],

            [
                'category' => 'Mortgage Liabilities',
                'description' => 'Long-term property financing obligations.'
            ],

            [
                'category' => 'Notes Payable',
                'description' => 'Formal written debt obligations.'
            ],

            [
                'category' => 'Deferred Tax Liabilities',
                'description' => 'Taxes payable in future accounting periods.'
            ],

            [
                'category' => 'Other Liabilities',
                'description' => 'Liabilities not classified elsewhere.'
            ],

        ];

        foreach ($categories as $category) {
            LiabilityCategory::updateOrCreate(
                ['category' => $category['category']],
                $category
            );
        }

    }
}

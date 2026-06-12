<?php

namespace Database\Seeders;

use App\Models\AssetsCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $categories = [

            [
                'category' => 'Operational Assets',
                'description' => 'Assets used in the daily operations of the business.'
            ],

            [
                'category' => 'Investment Assets',
                'description' => 'Assets held for investment purposes and future returns.'
            ],

            [
                'category' => 'Financial Assets',
                'description' => 'Cash, bank balances, receivables and other financial resources.'
            ],

            [
                'category' => 'Property Assets',
                'description' => 'Land, buildings and other real estate owned by the business.'
            ],

            [
                'category' => 'Equipment Assets',
                'description' => 'Machinery, computers, furniture and office equipment.'
            ],

            [
                'category' => 'Vehicle Assets',
                'description' => 'Cars, trucks, motorcycles and other company vehicles.'
            ],

            [
                'category' => 'Inventory Assets',
                'description' => 'Stock and goods held for sale or production.'
            ],

            [
                'category' => 'Intangible Assets',
                'description' => 'Software, patents, trademarks, copyrights and goodwill.'
            ],

            [
                'category' => 'Current Assets',
                'description' => 'Assets expected to be converted into cash within one year.'
            ],

            [
                'category' => 'Non-Current Assets',
                'description' => 'Assets expected to provide value beyond one year.'
            ],

            [
                'category' => 'Prepaid Assets',
                'description' => 'Expenses paid in advance such as rent and insurance.'
            ],

            [
                'category' => 'Other Assets',
                'description' => 'Assets that do not fit into other classifications.'
            ],

        ];

        foreach ($categories as $category) {
            AssetsCategories::updateOrCreate(
                ['category' => $category['category']],
                $category
            );
        }
    }
}

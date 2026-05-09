<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'barcode', 'category', 'brand', 'company_id', 'status', 'stock', 'unit_of_measure',
        'reorder_level', 'location', 'last_restocked_date', 'last_stock_movement', 'cost_per_unit', 'selling_price',
        'tax_vat', 'tax_vat_custom', 'profit_margin', 'supplier_id', 'expiry_date', 'product_description', 'image_path'
    ];

    protected $casts = [
        'last_restocked_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

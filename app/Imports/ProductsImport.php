<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Brand;
use App\Models\PriceRange;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Product([
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock_quantity' => $row['stock_quantity'],
            'brand_id' => $row['brand_id'],
            'price_range_id' => $row['price_range_id'],
            'image_path' => isset($row['image_path']) ? $row['image_path'] : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,brand_id',
            'price_range_id' => 'required|exists:price_ranges,price_range_id',
        ];
    }
}
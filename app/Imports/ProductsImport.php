<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Brand;
use App\Models\PriceRange;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Ensure all required fields are present and valid
            if (
                !empty($row['name']) &&
                isset($row['price']) &&
                isset($row['brand_id']) &&
                isset($row['price_range_id'])
            ) {
                Product::create([
                    'name' => $row['name'],
                    'description' => $row['description'] ?? '',
                    'price' => $row['price'],
                    'stock_quantity' => $row['stock_quantity'] ?? 0,
                    'brand_id' => $row['brand_id'],
                    'price_range_id' => $row['price_range_id'],
                    'image_path' => $row['image_path'] ?? 'products/default.jpg',
                ]);
            } 
        }
        dd($row);
    }
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            '*.name' => 'required|string|max:255',
            '*.price' => 'required|numeric|min:0',
        ];
    }
}
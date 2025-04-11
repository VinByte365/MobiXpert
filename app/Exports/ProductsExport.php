<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\PriceRange;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ProductsExport implements FromArray, WithHeadings, WithStrictNullComparison
{
    public function array(): array
    {
        // Get first brand and price range for sample
        $brand = Brand::first();
        $priceRange = PriceRange::first();
        
        if (!$brand || !$priceRange) {
            // Add note about creating brands and price ranges first
            return [
                [
                    'Sample Product', 
                    'This is a sample product description', 
                    '99.99', 
                    '10', 
                    'Create brands first', 
                    'Create price ranges first',
                    'products/default.png'
                ],
            ];
        }
        
        // Sample data with actual IDs
        return [
            [
                'Sample Product', 
                'This is a sample product description', 
                '99.99', 
                '10', 
                $brand->brand_id, 
                $priceRange->price_range_id,
                'products/default.png'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'stock_quantity',
            'brand_id',
            'price_range_id',
            'image_path',
        ];
    }
}
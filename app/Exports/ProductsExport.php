<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // For template, return empty or sample data
        return collect([
            [
                'name' => 'Sample Product',
                'description' => 'This is a sample product description',
                'price' => 499.99,
                'stock_quantity' => 100,
                'brand' => 'Sample Brand',
                'image_path' => 'products/sample.jpg'
            ]
        ]);
    }
    
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'name',
            'description',
            'price',
            'stock_quantity',
            'brand',
            'price_range_id',
            'image_path'
        ];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock_quantity' => $row['stock_quantity'],
            'brand' => $row['brand'],
            'price_range_id' => $row['price_range_id'],
            'image_path' => $row['image_path']
        ];
    }
}
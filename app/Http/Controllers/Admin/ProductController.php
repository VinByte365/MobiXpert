<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\PriceRange;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index()
    {
        try {
            // Get products with relationships
            $products = Product::with(['brand'])->get();
            
            // Debug information
            if ($products->isEmpty()) {
                session()->flash('notification', [
                    'type' => 'info',
                    'message' => "No products found in the database. Please add some products or import them."
                ]);
            }
            
            return view('admin.products.index', compact('products'));
        } catch (\Exception $e) {
            session()->flash('notification', [
                'type' => 'danger',
                'message' => "Error loading products: " . $e->getMessage()
            ]);
            
            return view('admin.products.index', ['products' => collect()]);
        }
    }

    public function create()
    {
        $brands = Brand::all();
        return view('admin.products.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,brand_id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock_quantity;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_path = $imagePath;
        }

        $product->save();

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Product created successfully!'
        ]);

        return redirect()->route('admin.products');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,brand_id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock_quantity;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_path = $imagePath;
        }

        $product->save();

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Product updated successfully!'
        ]);

        return redirect()->route('admin.products');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        session()->flash('notification', [
            'type' => 'success',
            'message' => 'Product deleted successfully!'
        ]);

        return redirect()->route('admin.products');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new ProductsImport(), $request->file('file'));
            
            session()->flash('notification', [
                'type' => 'success',
                'message' => 'Products imported successfully!'
            ]);
        } catch (\Exception $e) {
            session()->flash('notification', [
                'type' => 'danger',
                'message' => 'Error importing products: ' . $e->getMessage()
            ]);
        }
        
        return redirect()->route('admin.products');
    }

    public function template()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'description', 'price', 'stock_quantity', 'brand_id', 'price_range_id', 'image_path']);
            
            // Add a sample row
            fputcsv($file, [
                'Sample Product', 
                'This is a sample product description', 
                '999.99', 
                '100', 
                '1', // Replace with a valid brand_id
                '1',
                'products/sample.jpg' // Optional
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ProductDataTable;
use App\Models\Brand;
use App\Models\PriceRange;
use Illuminate\Support\Facades\Log;
use App\DataTables\UserDataTable;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use DB;
use App\DataTables\ReviewDataTable;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function products(ProductDataTable $dataTable)
    {
        $brands = Brand::all();
        $priceRanges = PriceRange::all();
        
        // Change from get() to paginate()
        $products = Product::with('brand')->paginate(10);
        
        return $dataTable->render('admin.products.index', compact('brands', 'priceRanges', 'products'));
    }

    public function getProductsData()
    {
        $products = Product::with('brand')->select(['product_id', 'name', 'price', 'stock_quantity', 'image_path', 'brand_id', 'created_at']);
        
        return DataTables::of($products)
            ->addColumn('image', function ($product) {
                if ($product->image_path) {
                    return '<img src="'.asset('storage/'.$product->image_path).'" alt="'.$product->name.'" width="50">';
                }
                return '<span class="text-muted">No image</span>';
            })
            ->addColumn('brand', function ($product) {
                return $product->brand ? $product->brand->name : 'N/A';
            })
            ->addColumn('price', function ($product) {
                return '$'.number_format($product->price, 2);
            })
            ->addColumn('actions', function ($product) {
                $editBtn = '<a href="'.route('admin.products.edit', $product->product_id).'" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $deleteBtn = '<form action="'.route('admin.products.destroy', $product->product_id).'" method="POST" class="d-inline">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this product?\')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>';
                return $editBtn . ' ' . $deleteBtn;
            })
            ->rawColumns(['image', 'actions'])
            ->make(true);
    }

    // Remove the getProducts method as it's redundant with the DataTable implementation
    // The getProductsData method handles the AJAX requests for the DataTable

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand_id' => 'required|exists:brands,brand_id',
            'price_range_id' => 'required|exists:price_ranges,price_range_id'
        ]);

        // Handle image upload
        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_path' => $imagePath,
            'brand_id' => $request->brand_id,
            'price_range_id' => $request->price_range_id
        ]);

        // Debug: Check if product was created
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Failed to create product');
        }

        return redirect()->route('admin.products')->with('success', 'Product added successfully (ID: '.$product->product_id.')');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'brand_id' => 'required|exists:brands,brand_id',
            'price_range_id' => 'required|exists:price_ranges,price_range_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'description', 'price', 'stock_quantity', 'brand_id', 'price_range_id']);

        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($product->image_path);
            // Store new image
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $name = $product->name;
            
            // Delete the image if it exists
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $product->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Product '{$name}' has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error deleting product: " . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Product $product)
    {
        $brands = Brand::all();
        $priceRanges = PriceRange::all();
        
        return view('admin.products.edit', compact('product', 'brands', 'priceRanges'));
    }

    // Add these methods to your AdminController

    public function users(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function getUsersData()
    {
        $users = User::select(['id', 'name', 'email', 'role', 'status', 'created_at']);
        return DataTables::of($users)->toJson();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,deactivated',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $name = $user->name;
            
            // Don't allow deleting yourself
            if (auth()->id() == $id) {
                return response()->json([
                    'success' => false,
                    'message' => "You cannot delete your own account."
                ], 403);
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => "User '{$name}' has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error deleting user: " . $e->getMessage()
            ], 500);
        }
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,deactivated',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('admin.users')->with('success', 'User created successfully');
    }

    // Add this method to your AdminController
    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('admin.users')->with('success', 'Users imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Error importing users: ' . $e->getMessage());
        }
    }
    
    // 6. Create a sample Excel template for users to download:
    public function exportUserTemplate()
    {
        return Excel::download(new UsersExport, 'users_template.xlsx');
    }

    public function importProducts(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        try {
            // Check if we have brands and price ranges
            $brandCount = Brand::count();
            $priceRangeCount = PriceRange::count();
            
            if ($brandCount == 0 || $priceRangeCount == 0) {
                return redirect()->route('admin.products')
                    ->with('error', 'You need to create brands and price ranges before importing products.');
            }
            
            // Import with transaction to ensure all or nothing
            \DB::beginTransaction();
            
            Excel::import(new ProductsImport, $request->file('file'));
            
            \DB::commit();
            return redirect()->route('admin.products')->with('success', 'Products imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            \DB::rollBack();
            
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: {$failure->errors()[0]}";
            }
            
            Log::error('Product import validation failed', ['errors' => $errors]);
            return redirect()->route('admin.products')
                ->with('error', 'Validation errors: ' . implode(', ', array_slice($errors, 0, 5)) . 
                    (count($errors) > 5 ? ' and ' . (count($errors) - 5) . ' more errors.' : ''));
        } catch (\Exception $e) {
            \DB::rollBack();
            
            Log::error('Product import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.products')
                ->with('error', 'Error importing products: ' . $e->getMessage());
        }
    }

    public function exportProductTemplate()
    {
        return Excel::download(new ProductsExport, 'products_template.xlsx');
    }

    public function reviews(ReviewDataTable $dataTable)
    {
        return $dataTable->render('admin.reviews.index');
    }

    public function destroyReview($id)
    {
        try {
            $review = Review::findOrFail($id);
            
            $review->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Review has been deleted successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Error deleting review: " . $e->getMessage()
            ], 500);
        }
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|password',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();
        
        return back()->with('success', 'Profile updated successfully');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = auth()->user();
        
        // Delete old photo if exists
        if ($user->profile_photo && Storage::exists('public/' . $user->profile_photo)) {
            Storage::delete('public/' . $user->profile_photo);
        }
        
        // Store new photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo = $path;
        $user->save();
        
        return back()->with('success', 'Profile photo updated successfully');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'notifications' => 'required|boolean',
        ]);
        
        // Store settings in session or user preferences
        session(['admin_theme' => $validated['theme']]);
        session(['admin_notifications' => $validated['notifications']]);
        
        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully');
    }

    public function create()
    {
        $brands = Brand::all();
        $priceRanges = PriceRange::all();
        return view('admin.products.create', compact('brands', 'priceRanges'));
    }
}
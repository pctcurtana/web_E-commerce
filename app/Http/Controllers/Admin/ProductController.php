<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock == 'in_stock') {
                $query->where('in_stock', true);
            } elseif ($request->stock == 'out_of_stock') {
                $query->where('in_stock', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        if (in_array($sort, ['name', 'price', 'stock_quantity', 'sold_count', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'manage_stock' => 'boolean',
        ]);

        $data = $request->all();
        
        // Set slug
        $data['slug'] = Str::slug($data['name']);
        
        // Handle stock status
        $data['in_stock'] = ($data['stock_quantity'] ?? 0) > 0;
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        // Set defaults
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['sold_count'] = 0;
        $data['average_rating'] = 0;
        $data['review_count'] = 0;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['manage_stock'] = $request->boolean('manage_stock', true);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load('category', 'reviews.user');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'manage_stock' => 'boolean',
        ]);

        $data = $request->all();
        
        // Update slug only if name changed
        if ($data['name'] !== $product->name) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Handle stock status
        $data['in_stock'] = ($data['stock_quantity'] ?? 0) > 0;
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old featured image
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        // Set defaults
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        $data['manage_stock'] = $request->boolean('manage_stock');

        $product->update($data);

        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        // Delete featured image
        if ($product->featured_image) {
            Storage::disk('public')->delete($product->featured_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    /**
     * Upload image and return path
     */
    private function uploadImage($image)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('products', $filename, 'public');
    }

    /**
     * Remove featured image from product
     */
    public function removeFeaturedImage(Product $product)
    {
        if ($product->featured_image) {
            // Only delete from storage if it's a local file (not external URL)
            if (!str_starts_with($product->featured_image, 'http')) {
                Storage::disk('public')->delete($product->featured_image);
            }
            
            $product->update(['featured_image' => null]);
            
            return redirect()->back()->with('success', 'Ảnh đại diện đã được xóa thành công!');
        }
        
        return redirect()->back()->with('error', 'Không tìm thấy ảnh đại diện để xóa!');
    }
}

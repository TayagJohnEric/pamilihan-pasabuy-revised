<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class VendorProductController extends Controller
{
    /**
     * Display a listing of the vendor's products.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
        }

        $products = Product::where('vendor_id', $vendor->id)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('vendor.product-management.index', compact('products'));
    }

    /**
 * Show the form for creating a new product.
 */
public function create()
{
    $vendor = Auth::user()->vendor;
    
    if (!$vendor) {
        return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
    }

    $categories = Category::orderBy('category_name')->get();
    
    return view('vendor.product-management.create', compact('categories'));
}

/**
 * Store a newly created product in storage.
 */
public function store(Request $request)
{
    $vendor = Auth::user()->vendor;
    
    if (!$vendor) {
        return redirect()->route('vendor.dashboard')->with('error', 'Vendor profile not found.');
    }

    $request->validate([
        'product_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'unit' => 'required|string|max:50',
        'pricing_model' => 'required|in:standard,budget_based',
        'price' => 'required|numeric|min:0', // Always required now
        'quantity_in_stock' => 'required|integer|min:0', // Always required now
        'indicative_price_per_unit' => 'required_if:pricing_model,budget_based|nullable|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'is_available' => 'boolean'
    ]);

    $imageUrl = null;
    if ($request->hasFile('image')) {
        $imageUrl = $request->file('image')->store('products', 'public');
    }

    $productData = [
        'vendor_id' => $vendor->id,
        'category_id' => $request->category_id,
        'product_name' => $request->product_name,
        'description' => $request->description,
        'unit' => $request->unit,
        'image_url' => $imageUrl,
        'is_available' => $request->has('is_available'),
        'price' => $request->price, // Always set the price
        'quantity_in_stock' => $request->quantity_in_stock, // Always set the stock
    ];

    if ($request->pricing_model === 'budget_based') {
        $productData['is_budget_based'] = true;
        $productData['indicative_price_per_unit'] = $request->indicative_price_per_unit;
    } else {
        $productData['is_budget_based'] = false;
        $productData['indicative_price_per_unit'] = null;
    }

    Product::create($productData);

    return redirect()->route('vendor.products.index')->with('success', 'Product created successfully!');
}

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Product not found.');
        }

        return view('vendor.product-management.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Product not found.');
        }

        $categories = Category::orderBy('category_name')->get();
        
        return view('vendor.product-management.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Product not found.');
        }

        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|string|max:50',
            'pricing_model' => 'required|in:standard,budget_based',
            'price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
            'indicative_price_per_unit' => 'required_if:pricing_model,budget_based|nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $productData = [
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'unit' => $request->unit,
            'is_available' => $request->has('is_available'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $productData['image_url'] = $request->file('image')->store('products', 'public');
        }

        // Handle pricing model aligning with create():
        // Always keep price and quantity as provided; toggle is_budget_based and indicative price accordingly
        if ($request->pricing_model === 'budget_based') {
            $productData['is_budget_based'] = true;
            $productData['indicative_price_per_unit'] = $request->indicative_price_per_unit;
        } else {
            $productData['is_budget_based'] = false;
            $productData['indicative_price_per_unit'] = null;
        }

        // Always set price and quantity in both models
        $productData['price'] = $request->price;
        $productData['quantity_in_stock'] = $request->quantity_in_stock;

        $product->update($productData);

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage (soft delete).
     */
    public function destroy(Product $product)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return redirect()->route('vendor.products.index')->with('error', 'Product not found.');
        }

        $product->delete(); // This will perform a soft delete

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Toggle product availability.
     */
    public function toggleAvailability(Product $product)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor || $product->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Product not found.'], 404);
        }

        $product->update(['is_available' => !$product->is_available]);

        return response()->json([
            'success' => true,
            'is_available' => $product->is_available,
            'message' => 'Product availability updated successfully!'
        ]);
    }
}

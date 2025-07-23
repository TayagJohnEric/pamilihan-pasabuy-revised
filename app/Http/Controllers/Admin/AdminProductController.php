<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    
 public function index(Request $request)
    {
        $query = Product::with(['vendor.user', 'category'])->orderBy('created_at', 'desc');

        if ($request->filled('is_available')) {
            $query->where('is_available', $request->is_available === '1');
        }

        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                        $vendorQuery->where('vendor_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $products = $query->paginate(15)->withQueryString();
        $vendors = Vendor::select('id', 'vendor_name')->orderBy('vendor_name')->get();

        return view('admin.platform-operation.products.index', compact('products', 'vendors'));
    }

    public function toggleAvailability($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_available' => !$product->is_available]);

        $status = $product->is_available ? 'enabled' : 'disabled';
        return back()->with('success', "Product {$product->product_name} has been {$status}");
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return back()->with('success', "Product {$product->product_name} has been removed");
    }

}

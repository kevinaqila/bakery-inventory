<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(10);
        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id'])
        ]);
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa membuat produk');
        }

        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Products/Create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa membuat produk');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:pcs,box',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa edit produk');
        }

        $categories = Category::where('is_active', true)->get();

        return Inertia::render('Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa edit produk');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' .$product->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit' => 'required|in:pcs,box',
            'purchase_price' => 'required|integer|min:0',
            'selling_price' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang bisa hapus produk');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

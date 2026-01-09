<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with(['product', 'user'])->latest()->paginate(15);

        return Inertia::render('stocks/Index', ['stocks' => $stocks]);
    }

    public function create()
    {
        $products = Product::all();

        return Inertia::render('stocks/Create', ['products' => $products]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);
        $validated['type'] = 'in';
        $validated['user_id'] = Auth::id();

        Stock::create($validated);

        $product = Product::findOrFail($validated['product_id']);
        $product->stock_quantity += $validated['quantity'];
        $product->save();

        return redirect()->route('stocks.index')->with('success', 'Laporan Stok berhasil dibuat.');
    }
}

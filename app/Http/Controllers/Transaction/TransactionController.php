<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['transactionItems.product', 'user'])->latest()->paginate(15);

        return Inertia::render('transactions/Index', ['transactions' => $transactions]);
    }

    public function create()
    {
        $products = Product::all();
        $user = Auth::user();

        return Inertia::render('transactions/Create', ['products' => $products, 'user' => $user]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_amount' => 'required|integer|min:0',
            'discount_amount' => 'required|integer|min:0',
            'final_amount' => 'required|integer|min:0',
            'payment_amount' => 'required|integer',
            'change_amount' => 'required|integer',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|integer|min:0',
            'items.*.subtotal' => 'required|integer|min:0',
        ]);

        // Validasi stock sebelum proses transaksi
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail((int) $item['product_id']);
            
            // Check apakah produk aktif
            if (!$product->is_active) {
                return redirect()->back()
                    ->withErrors([
                        'product_error' => "Produk {$product->name} tidak aktif."
                    ])
                    ->withInput();
            }


            // Check apakah produk cukup
            if ($product->stock_quantity < $item['quantity']) {
                return redirect()->back()
                    ->withErrors([
                        'stock_error' => "Stok {$product->name} tidak cukup. Tersedia {$product->stock_quantity} unit"
                    ])
                    ->withInput();
            }
        }

        $validated['user_id'] = Auth::id();
        
        // Simpan data dalam items
        $items = $validated['items'];
        unset($validated['items']);

        $transaction = Transaction::create($validated);

        foreach($items as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
            ]);

            // Update Stock product
            $product = Product::findOrFail((int) $item['product_id']);
            $product->stock_quantity -= $item['quantity'];
            $product->save();

            Stock::create([
                'product_id' => $item['product_id'],
                'type' => 'out',
                'quantity' => $item['quantity'],
                'notes' => 'Penjualan - Transaction #' . $transaction->id,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dilakukan.');
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['transactionItems.product', 'user']);

        return Inertia::render('transactions/Show', ['transaction' => $transaction]);
    }
}

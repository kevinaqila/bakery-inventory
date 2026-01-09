<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $userRole = Auth::user()->role;

        if($userRole === 'admin'){
            return $this->adminDashboard();
        } elseif($userRole === 'kasir'){
            return $this->kasirDashboard();
        } else if($userRole === 'karyawan'){
            return $this->karyawanDashboard();
        }
    }

    private function adminDashboard() {
        $totalTransactionMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalSaleMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');

        $totalTransactionYear = Transaction::whereYear('created_at', now()->year)->count();
        $totalSaleYear = Transaction::whereYear('created_at', now()->year)->sum('final_amount');

        $totalProduct = Product::where('is_active', true)->count();
        $totalUser = User::count();

        $topProducts = TransactionItem::selectRaw('
            product_id,
            products.name,
            products.purchase_price,
            products.selling_price,
            SUM(transaction_items.quantity) as total_qty,
            SUM(transaction_items.subtotal) as total_revenue,
            SUM(transaction_items.quantity * (products.selling_price - products.purchase_price)) as total_margin
        ')
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->groupBy('product_id', 'products.name', 'products.purchase_price', 'products.selling_price')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $salesChart = Transaction::selectRaw('
            DATE(created_at) as date,
            SUM(final_amount) as total
        ')
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Dashboard', [
            'totalTransaksisBulanIni' => $totalTransactionMonth,
            'totalPenjualanBulanIni' => $totalSaleMonth,
            'totalTransaksisahunIni' => $totalTransactionYear,
            'totalPenjualanTahunIni' => $totalSaleYear,
            'totalProduk' => $totalProduct,
            'totalUser' => $totalUser,
            'topProducts' => $topProducts,
            'salesChart' => $salesChart,
        ]);
    }

    private function kasirDashboard() {
        $totalTransactionMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('user_id', Auth::id())
            ->count();
        $totalSaleMonth = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('user_id', Auth::id())
            ->sum('final_amount');

        $topProducts = TransactionItem::selectRaw('
            product_id,
            products.name,
            SUM(transaction_items.quantity) as total_qty,
            SUM(transaction_items.subtotal) as total_revenue
        ')
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->join('transactions', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->whereMonth('transactions.created_at', now()->month)
            ->whereYear('transactions.created_at', now()->year)
            ->where('transactions.user_id', Auth::id())
            ->groupBy('product_id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $recentTransactions = Transaction::where('user_id', Auth::id())
            ->with('transactionItems')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'totalTransaksisBulanIni' => $totalTransactionMonth,
            'totalPenjualanBulanIni' => $totalSaleMonth,
            'topProducts' => $topProducts,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    private function karyawanDashboard() {
        $totalStokInMonth = Stock::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('user_id', Auth::id())
            ->where('type', 'in')
            ->count();

        $totalQuantityInMonth = Stock::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('user_id', Auth::id())
            ->where('type', 'in')
            ->sum('quantity');

        $recentStocks = Stock::where('user_id', Auth::id())
            ->with('product')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'totalStokMasukBulanIni' => $totalStokInMonth,
            'totalQuantityMasukBulanIni' => $totalQuantityInMonth,
            'recentStocks' => $recentStocks,
        ]);
    }
}

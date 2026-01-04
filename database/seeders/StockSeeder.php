<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $karyawans = User::where('role', 'karyawan')->get();
        $products = Product::all();

        if ($karyawans->isEmpty() || $products->isEmpty()) {
            return;
        }

        // Create initial stock for each product
        foreach ($products as $product) {
            $initialQuantity = rand(50, 200);
            
            Stock::create([
                'product_id' => $product->id,
                'type' => 'in',
                'quantity' => $initialQuantity,
                'notes' => 'Stok awal produk ' . $product->name,
                'user_id' => $karyawans->first()->id,
            ]);

            // Update product stock_quantity
            $product->increment('stock_quantity', $initialQuantity);
        }

        // Create 30 additional stock movements
        for ($i = 0; $i < 30; $i++) {
            $product = $products->random();
            $karyawan = $karyawans->random();
            $type = rand(0, 1) ? 'in' : 'out';
            $quantity = rand(5, 50);

            $notes = match ($type) {
                'in' => "Produksi shift " . rand(1, 3) . " - Batch " . rand(1, 10),
                'out' => "Pengurangan stok - Barang rusak/kadaluarsa",
                default => "Penyesuaian stok",
            };

            Stock::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'notes' => $notes,
                'user_id' => $karyawan->id,
            ]);

            // Update product stock_quantity based on type
            if ($type === 'in') {
                $product->increment('stock_quantity', $quantity);
            } else {
                $product->decrement('stock_quantity', $quantity);
            }
        }
    }
}

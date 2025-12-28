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
        $karyawan = User::where('email', 'karyawan@bakery.test')->first();
        $products = Product::all();

        if ($karyawan && $products->count() > 0) {
            // Add sample stock data
            Stock::create([
                'product_id' => $products[0]->id,
                'type' => 'in',
                'quantity' => 50,
                'notes' => 'Produksi pagi shift 1',
                'user_id' => $karyawan->id,
            ]);

            Stock::create([
                'product_id' => $products[1]->id,
                'type' => 'in',
                'quantity' => 30,
                'notes' => 'Produksi siang shift 2',
                'user_id' => $karyawan->id,
            ]);

            Stock::create([
                'product_id' => $products[2]->id,
                'type' => 'in',
                'quantity' => 25,
                'notes' => 'Produksi malam shift 3',
                'user_id' => $karyawan->id,
            ]);
        }
    }
}

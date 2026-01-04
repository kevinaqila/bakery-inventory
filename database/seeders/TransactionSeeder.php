<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kasirs = User::where('role', 'kasir')->get();
        $products = Product::all();

        if ($kasirs->isEmpty() || $products->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 20; $i++) {
            $kasir = $kasirs->random();
            
            $selectedProducts = $products->random(rand(2, 4));
            
            $totalAmount = 0;
            $discountAmount = rand(0, 50000); 
            
            $transaction = Transaction::create([
                'user_id' => $kasir->id,
                'total_amount' => 0, 
                'discount_amount' => $discountAmount,
                'final_amount' => 0, 
                'payment_amount' => 0, 
                'change_amount' => 0,
                'payment_method' => 'tunai',
                'notes' => 'Transaksi penjualan ' . now()->format('d M Y'),
            ]);

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 5);
                $unitPrice = $product->selling_price;
                $subtotal = $quantity * $unitPrice;
                $totalAmount += $subtotal;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }

            $finalAmount = $totalAmount - $discountAmount;
            $paymentAmount = $finalAmount + rand(0, 50000);
            $changeAmount = $paymentAmount - $finalAmount;

            $transaction->update([
                'total_amount' => $totalAmount,
                'final_amount' => $finalAmount,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
            ]);
        }
    }
}

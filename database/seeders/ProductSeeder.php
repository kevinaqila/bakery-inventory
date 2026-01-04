<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Donat
        Product::create([
            'name' => 'Donat Bomboloni',
            'sku' => 'DNT001',
            'description' => 'Donut asal Italia yang bentuknya bulat utuh tanpa lubang ditengahnya.',
            'category_id' => 1,
            'unit' => 'pcs',
            'purchase_price' => 6000,
            'selling_price' => 10000,
            'stock_quantity' => 15,
            'reorder_level' => 5,
        ]);

        Product::create([
            'name' => 'Donat Kentang',
            'sku' => 'DNT002',
            'description' => 'Donat yang dibuat dengan isian kentang manis.',
            'category_id' => 1,
            'unit' => 'pcs',
            'purchase_price' => 5000,
            'selling_price' => 8000,
            'stock_quantity' => 20,
            'reorder_level' => 5,
        ]);

        // Roti Manis
        Product::create([
            'name' => 'Roti Cokelat',
            'sku' => 'RTM001',
            'description' => 'Roti manis dengan isian cokelat premium.',
            'category_id' => 2,
            'unit' => 'pcs',
            'purchase_price' => 7000,
            'selling_price' => 12000,
            'stock_quantity' => 50,
            'reorder_level' => 5,
        ]);

        Product::create([
            'name' => 'Roti Keju',
            'sku' => 'RTM002',
            'description' => 'Roti manis dengan topping keju mozzarella.',
            'category_id' => 2,
            'unit' => 'pcs',
            'purchase_price' => 8000,
            'selling_price' => 13000,
            'stock_quantity' => 18,
            'reorder_level' => 5,
        ]);

        // Roti Tawar
        Product::create([
            'name' => 'Roti Tawar Putih',
            'sku' => 'RTT001',
            'description' => 'Roti tawar putih standar untuk sandwich.',
            'category_id' => 3,
            'unit' => 'box',
            'purchase_price' => 15000,
            'selling_price' => 22000,
            'stock_quantity' => 10,
            'reorder_level' => 3,
        ]);

        Product::create([
            'name' => 'Roti Tawar Gandum',
            'sku' => 'RTT002',
            'description' => 'Roti tawar dari gandum pilihan.',
            'category_id' => 3,
            'unit' => 'box',
            'purchase_price' => 18000,
            'selling_price' => 25000,
            'stock_quantity' => 8,
            'reorder_level' => 3,
        ]);

        // Kue Kering
        Product::create([
            'name' => 'Cookies Cokelat Chip',
            'sku' => 'KK001',
            'description' => 'Kue kering dengan chips cokelat.',
            'category_id' => 4,
            'unit' => 'box',
            'purchase_price' => 20000,
            'selling_price' => 30000,
            'stock_quantity' => 12,
            'reorder_level' => 5,
        ]);

        Product::create([
            'name' => 'Nastar',
            'sku' => 'KK002',
            'description' => 'Kue kering nastar dengan selai nanas.',
            'category_id' => 4,
            'unit' => 'box',
            'purchase_price' => 25000,
            'selling_price' => 35000,
            'stock_quantity' => 15,
            'reorder_level' => 5,
        ]);

        // Kue Basah
        Product::create([
            'name' => 'Bolu Kukus Cokelat',
            'sku' => 'KB001',
            'description' => 'Kue bolu kukus dengan rasa cokelat yang lembut.',
            'category_id' => 5,
            'unit' => 'pcs',
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock_quantity' => 30,
            'reorder_level' => 10,
        ]);

        Product::create([
            'name' => 'Brownies',
            'sku' => 'KB002',
            'description' => 'Brownies cokelat premium yang fudgy.',
            'category_id' => 5,
            'unit' => 'pcs',
            'purchase_price' => 12000,
            'selling_price' => 18000,
            'stock_quantity' => 25,
            'reorder_level' => 10,
        ]);

        // Pastry
        Product::create([
            'name' => 'Croissant Mentega',
            'sku' => 'PST001',
            'description' => 'Croissant dengan lapisan mentega premium.',
            'category_id' => 6,
            'unit' => 'pcs',
            'purchase_price' => 15000,
            'selling_price' => 25000,
            'stock_quantity' => 20,
            'reorder_level' => 5,
        ]);

        Product::create([
            'name' => 'Eclair',
            'sku' => 'PST002',
            'description' => 'Eclair dengan krim vanilla dan topping cokelat.',
            'category_id' => 6,
            'unit' => 'pcs',
            'purchase_price' => 18000,
            'selling_price' => 28000,
            'stock_quantity' => 15,
            'reorder_level' => 5,
        ]);
    }
}

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$products = \App\Models\Product::get(['id', 'name', 'stock_quantity']);

echo "=== Product Stock Status ===\n";
foreach ($products as $p) {
    echo $p->name . " -> Stock: " . $p->stock_quantity . "\n";
}

echo "\n=== Total Stock Summary ===\n";
echo "Total Products: " . $products->count() . "\n";
echo "Total Stock: " . $products->sum('stock_quantity') . "\n";

// Verify stock movements
$stocks = \App\Models\Stock::get(['product_id', 'type', 'quantity']);
echo "\nTotal Stock Movements: " . $stocks->count() . "\n";
echo "Stock In: " . $stocks->where('type', 'in')->sum('quantity') . "\n";
echo "Stock Out: " . $stocks->where('type', 'out')->sum('quantity') . "\n";

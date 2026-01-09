<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Optimize products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('purchase_price')->change();
            $table->unsignedInteger('selling_price')->change();
            $table->unsignedInteger('stock_quantity')->change();
        });

        // Optimize transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedInteger('total_amount')->change();
            $table->unsignedInteger('discount_amount')->change();
            $table->unsignedInteger('final_amount')->change();
            $table->unsignedInteger('payment_amount')->change();
            $table->unsignedInteger('change_amount')->change();
        });

        // Optimize transaction_items table
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->change();
            $table->unsignedInteger('unit_price')->change();
            $table->unsignedInteger('subtotal')->change();
        });

        // Optimize stocks table
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert products table
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_price')->change();
            $table->unsignedBigInteger('selling_price')->change();
            $table->integer('stock_quantity')->change();
        });

        // Revert transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('total_amount')->change();
            $table->unsignedBigInteger('discount_amount')->change();
            $table->unsignedBigInteger('final_amount')->change();
            $table->unsignedBigInteger('payment_amount')->change();
            $table->unsignedBigInteger('change_amount')->change();
        });

        // Revert transaction_items table
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->unsignedBigInteger('quantity')->change();
            $table->unsignedBigInteger('unit_price')->change();
            $table->unsignedBigInteger('subtotal')->change();
        });

        // Revert stocks table
        Schema::table('stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('quantity')->change();
        });
    }
};

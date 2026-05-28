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
        Schema::create('product_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Normal", "Regular", "20cm", "25cm"
            $table->decimal('additional_price', 10, 2)->default(0); // Additional charge from base price
            $table->integer('stock')->default(0);
            $table->boolean('is_available')->default(true);
            $table->integer('sort_order')->default(0); // For ordering sizes
            $table->timestamps();
        });

        // Add size_id to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_size_id')->nullable()->after('product_id')->constrained('product_sizes')->nullOnDelete();
            $table->string('size_name')->nullable()->after('product_size_id'); // Store size name for history
        });

        // Add size_id to carts table
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('product_size_id')->nullable()->after('product_id')->constrained('product_sizes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_size_id']);
            $table->dropColumn('product_size_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_size_id']);
            $table->dropColumn(['product_size_id', 'size_name']);
        });

        Schema::dropIfExists('product_sizes');
    }
};

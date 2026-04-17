<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('product_type', ['pre_order', 'ready_stock'])
                ->default('pre_order')
                ->after('is_active');
            $table->integer('lead_time_days')
                ->default(2)
                ->after('product_type')
                ->comment('Lead time in days for pre-order products');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'lead_time_days']);
        });
    }
};

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
        Schema::table('products', function (Blueprint $table) {
            // Drop unique index before dropping the column (required for SQLite)
            if (Schema::hasColumn('products', 'sku')) {
                // Default index name for a unique on `products`.`sku`
                $table->dropUnique('products_sku_unique');
                $table->dropColumn('sku');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            // Recreate the unique index if the column exists
            if (Schema::hasColumn('products', 'sku')) {
                $table->unique('sku');
            }
        });
    }
};

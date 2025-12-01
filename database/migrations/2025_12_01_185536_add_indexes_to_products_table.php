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
            // Add indexes for frequently queried columns
            $table->index('slug');
            $table->index('category_id');
            $table->index('price');
            $table->index(['category_id', 'price']); // Composite index for category and price
            $table->index('created_at');
            $table->index(['category_id', 'created_at']); // Composite index for category and creation date
        });

        // Add index to categories table as well
        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['price']);
            $table->dropIndex(['category_id', 'price']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['category_id', 'created_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });
    }
};

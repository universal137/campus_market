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
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'gallery')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('gallery')->nullable()->after('image_path');
            });
        } elseif (Schema::hasTable('items') && !Schema::hasColumn('items', 'gallery')) {
            Schema::table('items', function (Blueprint $table) {
                $table->json('gallery')->nullable()->after('image');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'gallery')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('gallery');
            });
        }

        if (Schema::hasTable('items') && Schema::hasColumn('items', 'gallery')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('gallery');
            });
        }
    }
};



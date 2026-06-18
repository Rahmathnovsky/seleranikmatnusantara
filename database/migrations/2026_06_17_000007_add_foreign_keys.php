<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add FK from promo_claims to outlets (after outlets table is created)
        Schema::table('promo_claims', function (Blueprint $table) {
            $table->foreign('used_at_outlet_id')->references('id')->on('outlets')->nullOnDelete();
        });

        // Add FK from promos to brands
        Schema::table('promos', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
        });

        // Add FK from blog_posts to brands
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('promo_claims', function (Blueprint $table) {
            $table->dropForeign(['used_at_outlet_id']);
        });

        Schema::table('promos', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
        });
    }
};

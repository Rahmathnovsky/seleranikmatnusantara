<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('terms')->nullable();
            $table->string('image')->nullable();
            $table->string('banner_image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('max_claims')->nullable()->comment('null = unlimited');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->enum('promo_type', ['percentage', 'fixed', 'free_item', 'buy_x_get_y'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->string('discount_label')->nullable()->comment('e.g. "Diskon 50%", "Gratis 1 produk"');
            $table->boolean('requires_login')->default(true);
            $table->timestamps();
        });

        Schema::create('promo_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('claim_code')->unique();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('used_at_outlet_id')->nullable();
            $table->enum('status', ['claimed', 'used', 'expired'])->default('claimed');
            $table->timestamps();

            $table->unique(['promo_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_claims');
        Schema::dropIfExists('promos');
    }
};

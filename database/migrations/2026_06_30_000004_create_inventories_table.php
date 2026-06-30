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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                ->constrained('product_variants')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('store_id')
                ->constrained('stores')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('current_stock')->default(0);
            $table->unsignedInteger('required_stock')->default(0);
            $table->unsignedInteger('weekly_sales')->default(0);
            $table->date('last_checked_at')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

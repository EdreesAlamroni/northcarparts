<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_cross_references', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('reference_code');
            $table->string('reference_code_normalized');

            $table->unique(['product_id', 'brand_id', 'reference_code_normalized'], 'product_cross_reference_unique');
            $table->index(['brand_id', 'reference_code_normalized'], 'product_cross_reference_brand_lookup');
            $table->index('reference_code_normalized', 'product_cross_reference_reference_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_cross_references');
    }
};

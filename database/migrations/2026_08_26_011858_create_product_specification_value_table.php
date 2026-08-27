<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specification_value', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specification_value_id')->constrained()->cascadeOnDelete();

            $table->unique(['product_id', 'specification_value_id'], 'product_specification_value_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specification_value');
    }
};

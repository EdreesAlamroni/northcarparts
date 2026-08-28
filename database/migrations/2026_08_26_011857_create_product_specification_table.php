<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_specification', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specification_id')->constrained()->cascadeOnDelete();
            $table->string('value');

            $table->unique(['product_id', 'specification_id'], 'product_specification_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_specification');
    }
};

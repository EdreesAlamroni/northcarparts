<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('oem_manufacturer_id')->nullable()->constrained('manufacturers')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->string('oem_number')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->string('state');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

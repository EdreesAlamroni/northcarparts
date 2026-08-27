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
            $table->string('name')->nullable();
            $table->string('filter_type');
            $table->string('oem_number')->nullable();
            $table->string('qr_code_redirect_url')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('state');
            $table->timestamps();

            $table->index('oem_number');
            $table->index('filter_type');
            $table->index(['category_id', 'state', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['categories', 'products', 'news'] as $table) {
            DB::table($table)
                ->where('state', 'draft')
                ->update(['state' => 'hidden']);

            DB::table($table)
                ->where('state', 'published')
                ->update(['state' => 'visible']);
        }
    }

    public function down(): void
    {
        foreach (['categories', 'products', 'news'] as $table) {
            DB::table($table)
                ->where('state', 'hidden')
                ->update(['state' => 'draft']);

            DB::table($table)
                ->where('state', 'visible')
                ->update(['state' => 'published']);
        }
    }
};

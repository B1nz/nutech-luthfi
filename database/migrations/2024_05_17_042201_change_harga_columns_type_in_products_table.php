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
        Schema::table('products', function (Blueprint $table) {
            // Change harga_beli column type to string
            $table->string('harga_beli')->change();

            // Change harga_jual column type to string
            $table->string('harga_jual')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert harga_beli column type to integer (assuming it was integer before)
            $table->integer('harga_beli')->change();

            // Revert harga_jual column type to integer (assuming it was integer before)
            $table->integer('harga_jual')->change();
        });
    }
};

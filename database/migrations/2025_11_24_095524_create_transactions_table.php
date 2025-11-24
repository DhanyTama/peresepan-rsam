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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi')->unique();
            $table->unsignedBigInteger('resep_id');
            $table->unsignedBigInteger('apoteker_id');
            $table->decimal('total_harga', 10, 2)->minimum(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('resep_id')->references('id')->on('reseps')->onDelete('cascade');
            $table->foreign('apoteker_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['resep_id']);
            $table->dropForeign(['apoteker_id']);
        });
        Schema::dropIfExists('transactions');
    }
};

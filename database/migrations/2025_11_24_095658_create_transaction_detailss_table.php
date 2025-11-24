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
        Schema::create('transaction_detailss', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('obat_id');
            $table->integer('jumlah')->minimum(1);
            $table->decimal('harga_satuan', 10, 2)->minimum(0);
            $table->decimal('subtotal', 10, 2)->minimum(0);
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('obat_id')->references('id')->on('obats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_detailss', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['obat_id']);
        });
        Schema::dropIfExists('transaction_detailss');
    }
};

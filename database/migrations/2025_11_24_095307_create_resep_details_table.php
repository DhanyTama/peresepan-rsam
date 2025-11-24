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
        Schema::create('resep_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resep_id');
            $table->unsignedBigInteger('obat_id');
            $table->integer('jumlah')->minimum(1);
            $table->integer('dosis')->minimum(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('resep_id')->references('id')->on('reseps')->onDelete('cascade');
            $table->foreign('obat_id')->references('id')->on('obats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_details', function (Blueprint $table) {
            $table->dropForeign(['resep_id']);
            $table->dropForeign(['obat_id']);
        });
        Schema::dropIfExists('resep_details');
    }
};

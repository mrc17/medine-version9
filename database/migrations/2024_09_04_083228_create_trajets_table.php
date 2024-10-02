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
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();
            $table->string('depart');
            $table->string('arrivee');
            $table->decimal('prix', 8, 2);
            $table->foreignId('gare_id')->constrained('gares')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('mode_depart_id')->constrained('mode_departs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajets');
    }
};

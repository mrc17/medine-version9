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
        Schema::create('gares', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('ville');
            $table->string('commune');
            $table->boolean('valide')->default(false);
            $table->foreignId('comptable_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responsable_gare_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caisse_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gares');
    }
};

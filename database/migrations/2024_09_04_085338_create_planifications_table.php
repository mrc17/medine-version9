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
        Schema::create('planifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->nullable()->constrained('cars')->onDelete('set null')->onUpdate('set null');
            $table->foreignId('gare_id')->nullable()->constrained('gares')->onDelete('set null')->onUpdate('set null');
            $table->foreignId('trajet_id')->nullable()->constrained('trajets')->onDelete('set null')->onUpdate('set null');
            $table->date('date');
            $table->string('codedepart');
            $table->string('heure');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planifications');
    }
};

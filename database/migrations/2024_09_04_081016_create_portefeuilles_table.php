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
        Schema::create('portefeuilles', function (Blueprint $table) {
            $table->id();
            $table->decimal('commission', 8, 2);
            $table->decimal('montant_ticket', 8, 2);
            $table->integer('attempt_logins')->default(0);
            $table->string('numero_depot')->default('+2250757346565'); //+2250757346565
            $table->string('password')->default('$2y$10$R7MSTLTDaReL/bLQknoel.qKx54/ocYussbQ9muThDRz97sgpGA9.'); //2580
            $table->foreignId('compagnie_id')->constrained('compagnies')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portefeuilles');
    }
};

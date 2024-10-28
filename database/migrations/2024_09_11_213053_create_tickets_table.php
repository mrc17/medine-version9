<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compagnie_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('depart');
            $table->string('arrivee');
            $table->date('date_reservation')->nullable(); // Utilisé si la réservation est confirmée
            $table->time('heure_depart')->nullable(); // Utilisé si la réservation est confirmée
            $table->string('status'); // "Attente" ou "Confirmé"
            $table->string('mode_paiement'); // Mode de paiement
            $table->foreignId('gare_id')->constrained()->onDelete('cascade');
            $table->string('codedepart')->nullable(); // Utilisé si la réservation est confirmée
            $table->string('reference')->unique(); // Référence unique du ticket
            $table->decimal('tarif', 10, 2); // Prix du ticket
            $table->string('numero_paiement'); // Numéro de paiement (ou téléphone)
            $table->string('codeticket')->nullable(); // Code du ticket
            $table->decimal('montant_ttc', 10, 2); // Montant total TTC
            $table->string('num_ticket')->unique(); // Numéro du ticket
            $table->integer('place')->nullable(); // Numéro de place si confirmé
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

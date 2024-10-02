<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\GareController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TrajetController;
use App\Http\Controllers\Api\EmployeController;
use App\Http\Controllers\Api\CompagnieController;
use App\Http\Controllers\Api\TelephoneController;
use App\Http\Controllers\Api\CommissionController;
use App\Http\Controllers\Api\PayerTicketController;
use App\Http\Controllers\Api\PortefeuilleController;
use App\Http\Controllers\Api\TrajetCaisseController;
use App\Http\Controllers\Api\DetailsCaisseController;
use App\Http\Controllers\Api\PlanificationController;
use App\Http\Controllers\Api\PayerTicketCaisseController;
use App\Http\Controllers\Api\CreateUserCompagnieController;
use App\Http\Controllers\Api\ResponsableEmployeeController;
use App\Http\Controllers\Api\VerifieConnectAdminController;
use App\Http\Controllers\Api\AuthentificationAdminController;
use App\Http\Controllers\Api\AuthentificationClientController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes protégées nécessitant l'authentification via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', LogoutController::class);
    //Route pour les gare
    Route::post("/gare", [GareController::class, "store"]);
    Route::patch("/gare/{gare_id}", [GareController::class, "show"]);
    Route::get("/gare/{gare_id}", [GareController::class, "destroy"]);
    Route::patch("/gare/{gare_id}/historique", [GareController::class, "showhistorique"]);
    Route::get("/compagnie/gares/{compagnie_id}", [GareController::class, "compagniegare"]);
    Route::get("compagnie/gare/{gare_id}/tickets", [GareController::class, "compagniegaretickets"]);
    Route::patch("/suspendre/compagnie/{compagnie_id}/gare/{id_gare}", [GareController::class, "etat"]);
    Route::patch("/gare/{compagnie_id}/{id_comptable}/{id_gare}", [GareController::class, "changecomptable"]);
    Route::patch("/gare/{compagnie_id}/{id_responsable}/{id_gare}", [GareController::class, "changeresponsable"]);
    //Route les Cars
    Route::post('/car', [CarController::class, 'store']);
    Route::patch('/car/{car_id}/etat', [CarController::class, 'etat']);
    Route::get('/compagnie/cars', [CarController::class, 'compagniecars']);
    Route::delete('/car/{car_id}/supprime', [CarController::class, 'destroy']);
    Route::get('/compagnie/gare/cars', [CarController::class, 'carCompagnieTogares']);
    // Route employees
    Route::post('/compagnie/employees', [EmployeController::class, 'store']);
    Route::post('/compagnie/employees', [EmployeController::class, 'destroy']);;
    Route::put('/compagnie/employees/', [EmployeController::class, "updatePassword"]);
    Route::get('/compagnie/employees', [EmployeController::class, 'compagnieemployees']);
    Route::post('/compagnie/gare/employees/change', [EmployeController::class, 'change']);
    Route::post('/compagnie/employees/suspendre', [EmployeController::class, 'suspendre']);
    Route::get('/compagnie/employees/{employe_id}', [EmployeController::class, 'profilUser']);
    Route::post('/compagnie/gare/employees/associer', [EmployeController::class, 'assignToStation']);
    //Route pour creer un caissier par un responsables
    Route::get('/employe/responsable/caisse', [ResponsableEmployeeController::class, 'get']);
    Route::post('/employe/responsable/caisse', [ResponsableEmployeeController::class, "store"]);
    Route::delete('/employe/responsable/caisse', [ResponsableEmployeeController::class, 'delete']);
    Route::patch('/employe/responsable/caisse', [ResponsableEmployeeController::class, "supendre"]);
    Route::put('/employe/responsable/caisse', [ResponsableEmployeeController::class, 'updatePassword']);

    //Route pour les trajets
    Route::post('/compagnie/gare/trajets', [TrajetController::class, 'store']);
    Route::get('/compagnie/gare/trajets', [TrajetController::class, 'garetrajets']);
    Route::put('/compagnie/gare/trajets', [TrajetController::class, 'updatePrice']);
    Route::delete('/compagnie/gare/trajets/{trajet_id}', [TrajetController::class, 'destroy']);
    //Route pour les Planification
    Route::post('/compagnie/gare/planification', [PlanificationController::class, 'store']);
    Route::get('/compagnie/gare/planification', [PlanificationController::class, 'gareplanifications']);
    Route::delete('/compagnie/gare/planification/{trajet_id}', [PlanificationController::class, 'destroy']);

    Route::post('/gare/trajet', [TrajetController::class, "show"]);
    Route::get("/compagnies", [CompagnieController::class, "index"]);
    Route::get('/gare/{gare_id}/trajets', [TrajetController::class, "trajetsGare"]);
    Route::get("/compagnie/{compagnie_id}/gares", [GareController::class, "compagniegares"]);
    Route::post('/trajet/commission', [CommissionController::class, 'show']);
    Route::get('/planification/{gare_id}/valide', [PlanificationController::class, "planificationvalide"]);
    //Ticket
    Route::post("/ticket/payer", PayerTicketController::class);
    Route::get('/ticket/user', [TicketController::class, "ticketuser"]);
    Route::post("/ticket/payer/cassiere", PayerTicketCaisseController::class);
    Route::get('/gars/tickets/', [TicketController::class, "garstickets"]);
    Route::put('/tickets/scanner', [TicketController::class, "ticketscanner"]);
    Route::get('/compagnie/tickets/', [TicketController::class, "compagnietickets"]);
    Route::get('/tickets/jour/gare', [TicketController::class, "ticketsByDayForGare"]);
    Route::get('/tickets/jour/compagnie', [TicketController::class, "ticketsByDayForCompagnie"]);
    Route::get('/ticket/revenue/jour/gare', [TicketController::class, "ticketsRevenueByDayForGare"]);
    Route::get('/tickets/scanner/jour/gare', [TicketController::class, "ticketsScanneByDayForGare"]);
    Route::get('/ticket/revenue/aujourdhui/gare', [TicketController::class, "ticketsRevenueByAujourdhuiForGare"]);

    Route::post('portefeuille/unlock', [PortefeuilleController::class, 'unlock']);
    Route::get('portefeuille/valeur', [PortefeuilleController::class, 'valeur']);
    Route::get('portefeuille/retrait', [PortefeuilleController::class, 'retrait']);
    Route::get('/portefeuille/historique', [PortefeuilleController::class, "historique"]);
    Route::get('/change-wallet-code', [PortefeuilleController::class, "changewalletcode"]);

    Route::get('caisse/info/trajets/caisse', [TrajetCaisseController::class, 'infotrjets']);
    Route::post('caisse/info/trajet', [TrajetCaisseController::class, 'infotrjet']);
    Route::get('caisse/ticket/user/sold', DetailsCaisseController::class);
    //User
    Route::put('/user', [UserController::class, 'update']);
    Route::delete('/user/delete', [UserController::class, 'destroy']);

});
Route::put('/telephone/retrouve', [TelephoneController::class, "show"]);
Route::post("/connexion/client", AuthentificationClientController::class);
Route::post('/inscription/telephone', [TelephoneController::class, "store"]);
Route::post('/user/update/password', [TelephoneController::class, "updatepassword"]);
Route::put('/inscription/telephone/confirmation', [TelephoneController::class, "update"]);
Route::put('telephone/confirmation/password', [TelephoneController::class, "confirmationpassword"]);

Route::post('/connexion/admin', AuthentificationAdminController::class);
Route::post('/inscription/compagnie', CreateUserCompagnieController::class);
Route::post('/connexion/admin/verifie', VerifieConnectAdminController::class);

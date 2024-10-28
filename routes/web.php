<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\GareController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompagnieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AffiliationController;
use App\Http\Controllers\InfoEmployeController;
use App\Http\Controllers\PortefeuilleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get("/gares", [GareController::class, 'index'])->name("gares.index");
    Route::get('/gare/{gare}', [GareController::class, 'show'])->name("gare.show");
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get("/portefeuille", PortefeuilleController::class)->name('portefeuille.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('gare/{gare}', [GareController::class, 'delete'])->name('gare.delete');
    Route::post('/employes', [InfoEmployeController::class, 'store'])->name('employe.store');
    Route::put('gare/update/{gare}', [GareController::class, 'update'])->name('gare.update');
    Route::get("/utilisateur", [UserController::class, 'index'])->name('utilisateurs.index');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/employes', [InfoEmployeController::class, 'index'])->name("employees.index");
    Route::get('/dashboard', [DashboardController::class, 'dashbordadmin'])->name('dashboard');
    Route::get("/compagnies", [CompagnieController::class, 'index'])->name("compagnies.index");
    Route::get('gare/update/{gare}', [GareController::class, 'restaure'])->name('gare.restaure');
    Route::get("demandes", [CompagnieController::class, 'demandes'])->name("compagnies.demandes");
    Route::get('/employes/{employe}', [InfoEmployeController::class, 'show'])->name('employe.show');
    Route::post("/affiliation", [AffiliationController::class, 'store'])->name("affiliation.store");
    Route::get("/affiliations", [AffiliationController::class, 'index'])->name("affiliations.index");
    Route::delete("/utilisateur/{utilisateur}", [UserController::class, 'delete'])->name('utilisateur.delete');
    Route::get("/utilisateur/{utilisateur}", [UserController::class, 'show'])->name('utilisateur.show');
    Route::get("/compagnie/{compagnie}", [CompagnieController::class, 'show'])->name("compagnies.show");
    Route::get('/affiliations/total-taux/{compagnie_id}', [AffiliationController::class, 'getTotalTaux']);
    Route::patch('/employes/{employe}', [InfoEmployeController::class, 'update'])->name('employee.update');
    Route::patch('/employes/{employe}', [InfoEmployeController::class, 'destroy'])->name("employe.delete");
    Route::patch("/compagnie/{compagnie}", [CompagnieController::class, 'valide'])->name("compagnie.valide");
    Route::patch("/compagnie/{compagnie}", [CompagnieController::class, 'delete'])->name("compagnie.delete");
    Route::get("/affiliations/{affiliation}", [AffiliationController::class, 'show'])->name("affiliation.show");
    Route::delete("/compagnie/{compagnie}", [CompagnieController::class, 'destroy'])->name("compagnie.destroy");
    Route::patch('/employees/{employee}/reset-password', [InfoEmployeController::class, 'resetPassword'])->name('employee.resetPassword');
    Route::get('compagnies/demandes/count',[CompagnieController::class, 'count'])->name("compagnies.demandes.count");
});

require __DIR__ . '/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompagnieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Routes pour les opérations Web sur les compagnies
//Route::resource('compagnie', CompagnieController::class);

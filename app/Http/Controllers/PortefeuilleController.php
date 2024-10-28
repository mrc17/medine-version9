<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PortefeuilleController extends Controller
{
    public function __invoke()
    {
        return Inertia('Portefeuille/Index',[
            "auth"=>auth()->user(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Commission;
use App\Models\Compagnie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommissionController extends Controller
{
    public function show(Request $request)
    {

        $sig = $request->input("compagnie");
        $montant = $request->input("montant");

        // Récupération de la compagnie par son sig
        $compagnie = Compagnie::where('sig', $sig)->first();

        if (!$compagnie) {
            return response()->json([
                'success' => false,
                'status_code' => 404,
                'message' => 'Compagnie non trouvée.',
            ], 404);
        }

        $compagnie_id = $compagnie->id;

        // Vérification de la commission
        $commission = Commission::where([
            'compagnie_id' => $compagnie_id,
            'montant' => $montant
        ])->first();

        if (!$commission) {
            return response()->json([
                'success' => true,
                'status_code' => 200,
                'commission' => $this->getfraiservice($montant),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'status_code' => 200,
            'commission' => $commission->valeur + $this->getfraiservice($montant),
        ], 200);
    }

    private function getfraiservice($montant)
    {
        $fraisIntouch = $montant * env("FRAISOPERATEUR");
        $totaleFrais = $fraisIntouch + env("FRAISSERVICE");
        return $totaleFrais;
    }
}

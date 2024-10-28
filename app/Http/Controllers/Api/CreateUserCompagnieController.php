<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\User;
use App\Models\Compagnie;
use Illuminate\Support\Str;
use App\Models\Portefeuille;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\CreateUserCompagnieRequest;

class CreateUserCompagnieController extends Controller
{
    /**
     * Handle the creation of a new company along with its responsible user and wallet.
     *
     * @param CreateUserCompagnieRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(CreateUserCompagnieRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();  // Validate the request
            $imagePath = null;

            // Check if the request contains an image and handle it
            if ($request->has('image')) {
                $base64Image = $request->input('image');

                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    // Extract base64 encoded image data
                    $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                    $imageData = base64_decode($imageData);

                    if ($imageData === false) {
                        return response()->json(['success' => false, 'message' => 'Invalid image data'], 400);
                    }
                } else {
                    return response()->json(['success' => false, 'message' => 'Image format not supported'], 400);
                }

                // Generate a unique file name for the image
                $fileName = Str::uuid() . '.png';
                $filePath = public_path('images/compagnies/') . $fileName;

                // Create the directory if it doesn't exist
                if (!File::exists(public_path('images/compagnies/'))) {
                    File::makeDirectory(public_path('images/compagnies/'), 0755, true);
                }

                // Save the image to the server
                File::put($filePath, $imageData);
                $imagePath = 'images/compagnies/' . $fileName;
            }

            // Create the responsible user
            $responsable = User::create([
                'nom' => $data['nom_responsable'],
                'prenom' => $data['prenom_responsable'],
                'telephone' => $data['telephone_responsable'],
                'password' => Hash::make($data['password']),
                'role_id' => 1,  // Ensure this matches your roles setup
            ]);

            // Create the company
            $compagnie = Compagnie::create([
                'nom' => $data['nom_compagnie'],
                'sig' => $data['sig'],
                'localite' => $data['localite'],
                'contact' => $data['telephone_compagnie'],
                'image' => $imagePath,
                'responsable_id' => $responsable->id,
            ]);

            // Create the wallet for the company
            Portefeuille::create([
                'commission' => 0,
                'montant_ticket' => 0,
                'numero_depot' => $data['telephone_compagnie'],
                'password' => bcrypt(2580),  // Consider using a stronger password or generating it dynamically
                'compagnie_id' => $compagnie->id,
                'attempt_logins' => 0,
            ]);

            // Commit the transaction if everything is successful
            DB::commit();

            return response()->json([
                'success' => true,
                'status_code' => 201,
                'message' => 'Compagnie créée avec succès',
                'data' => $compagnie,
            ], 201);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();

            // Log the error with additional request information
            Log::error('Error while creating company: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Erreur lors de la création de la compagnie',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

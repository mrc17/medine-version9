<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Compagnie;
use Illuminate\Support\Str;
use App\Models\Portefeuille;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Exception\RequestException;
use App\Http\Requests\Api\CreateUserCompagnieRequest;

class CreateUserCompagnieController extends Controller
{
    public function __invoke(CreateUserCompagnieRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $imagePath = null;

            if ($request->has('image')) {
                $base64Image = $request->input('image');
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $data = substr($base64Image, strpos($base64Image, ',') + 1);
                    $data = base64_decode($data);
                    if ($data === false) {
                        return response()->json(['success' => false, 'message' => 'Invalid image data'], 400);
                    }
                }

                $fileName = Str::uuid() . '.png';
                $filePath = public_path('images/compagnies/') . $fileName;

                if (!File::exists(public_path('images/compagnies/'))) {
                    File::makeDirectory(public_path('images/compagnies/'), 0755, true);
                }
                File::put($filePath, $data);
                $imagePath = 'images/compagnies/' . $fileName;
            }

            $responsable = User::create([
                'nom' => $data['nom_responsable'],
                'prenom' => $data['prenom_responsable'],
                'telephone' => $data['telephone_responsable'],
                'password' => Hash::make($data['password']),
                'role_id' => 1,
            ]);

            $compagnie = Compagnie::create([
                'nom' => $data['nom_compagnie'],
                'sig' => $data['sig'],
                'localite' => $data['localite'],
                'contact' => $data['telephone_compagnie'],
                'image' =>  $this->removeBackground($imagePath),
                'responsable_id' => $responsable->id
            ]);

            Portefeuille::create([
                'commission' => 0,
                'montant_ticket' => 0,
                "numero_depot" => $data['telephone_compagnie'],
                "password" => bcrypt(2580),
                'compagnie_id' => $compagnie->id,
                "attempt_logins" => 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'status_code' => 201,
                'message' => 'Compagnie créée avec succès',
                'data' => $compagnie
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function removeBackground($filePath)
    {
        $client = new Client();
        $apiKey = env('REMOVE_BG_API_KEY');

        $image = fopen(public_path($filePath), 'r');
        dd($image);

        try {
            $response = $client->request('POST', 'https://api.remove.bg/v1.0/removebg', [
                'headers' => [
                    'X-Api-Key' => $apiKey,
                ],
                'multipart' => [
                    [
                        'name' => 'image_file',
                        'contents' => $image,
                    ],
                    [
                        'name' => 'size',
                        'contents' => 'auto',
                    ],
                ],
                'sink' => public_path('images/compagnies/processed_' . basename($filePath)),
            ]);

            fclose($image);

            if ($response->getStatusCode() == 200) {
                return 'images/compagnies/processed_' . basename($filePath);
            }
        } catch (RequestException $e) {
            Log::error('Remove.bg error: ' . $e->getMessage());
        }

        return $filePath;
    }
}

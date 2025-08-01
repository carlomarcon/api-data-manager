<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      "email" => "required|email",
      "password" => "required",
    ]);

    $user = User::where("email", $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        "email" => ["Le credenziali fornite non sono corrette."],
      ]);
    }

    // Crea e restituisci il token
    $token = $user->createToken("api-token")->plainTextToken;

    return response()->json(["token" => $token]);
  }

  public function logout(Request $request)
  {
    // Revoca il token usato per l'autenticazione della richiesta
    $request->user()->currentAccessToken()->delete();
    return response()->json(["message" => "Logout effettuato con successo."]);
  }
}

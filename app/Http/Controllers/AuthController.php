<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthRequest $request){
          // On récupère les données validées de la requête
    $credentials = $request->only('name', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); 
        return response()->json(['message' => 'Authenticated']);
    }

    // Si l'authentification échoue
    return response()->json(['message' => 'Invalid credentials'], 401);

    }

    public function logout(){
         Auth::guard('web')->logout();
        
        // Invalider la session
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Préparer la réponse en supprimant les cookies
        return response()->json(['message' => 'Déconnecté avec succès'], 200);
            
    }
}

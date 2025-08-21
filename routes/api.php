<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoinController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CultureController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VenteController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json($user, 201);
});

Route::prefix('/auth')->controller(AuthController::class)->group(function(){
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');
});

Route::prefix('/produit')->controller(ProduitController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/create','create');
    Route::put('/update/{id}','update');
    Route::delete('/delete/{id}','delete');
});

Route::prefix('/animal')->controller(AnimalController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/create','create');
    Route::put('/update/{id}','update');
    Route::delete('/delete/{id}','delete');
});

Route::prefix('/stock')->controller(StockController::class)->group(function(){
    Route::get('/', 'index'); // Liste des stocks
    Route::post('/ajouter', 'ajouter'); // Ajouter au stock
    Route::post('/vendre', 'vendre'); 
});

Route::prefix('/client')->controller(ClientController::class)->group(function(){
    Route::get('/', 'index');
    Route::post('/create','create');
    Route::put('/update/{id}','update');
    Route::delete('/delete/{id}','delete');
});

Route::prefix('/vente')->controller(VenteController::class)->group(function(){
    Route::get('/', 'index');
    Route::post('/create','create');
    Route::put('/update/{id}','update');
    Route::delete('/delete/{id}','delete');
    Route::get('/histo','statistiquesMensuelles');
});





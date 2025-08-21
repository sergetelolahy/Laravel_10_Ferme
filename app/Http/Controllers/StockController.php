<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Requests\StockFormRequest;

class StockController extends Controller
{
    public function ajouter(StockFormRequest $request)
    {
        // Validation est automatiquement faite grâce à StockRequest
        $validated = $request->validated(); // Récupère uniquement les champs validés

        // Vérifie si un produit du type existe déjà dans le stock
        $stock = Stock::where('type_produit', $validated['type_produit'])->first();

        if (!$stock) {
            // Si le stock n'existe pas, on le crée
            $stock = Stock::create([
                'type_produit' => $validated['type_produit'],
                'quantite_stock' => $validated['quantite'],
            ]);
        } else {
            // Sinon, on ajoute la quantité au stock existant
            $stock->quantite += $validated['quantite'];
            $stock->save();
        }

        return response()->json($stock);
    }

    // Vendre des produits du stock
    public function vendre(StockFormRequest $request)
    {
        $validated = $request->validated();

        $stock = Stock::where('type_produit', $validated['type_produit'])->first();

        if ($stock) {
            try {
                $stock->vendreProduit($validated['quantite']);
                return response()->json($stock);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }
        }

        return response()->json(['error' => 'Produit non trouvé'], 404);
    }

    // Afficher les stocks
    public function index()
    {
        $stocks = Stock::all();
        return response()->json($stocks);
    }
}

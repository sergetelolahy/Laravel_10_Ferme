<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Vente;
use Illuminate\Http\Request;
use App\Http\Requests\VenteFormRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    // 📄 Afficher toutes les ventes
    public function index()
    {
        $ventes = Vente::with(['client','stock'])
                      ->orderByDesc('created_at')
                      ->get();
    
        return $ventes;
    }
    

    // ➕ Créer une vente
    public function create(VenteFormRequest $request)
    {
        $validated = $request->validated();
    
        // Récupérer le stock correspondant
        $stock = Stock::find($validated['stock_id']);
    
        if (!$stock) {
            return response()->json([
                'message' => 'Stock introuvable.'
            ], 404);
        }
    
        // Vérifier la quantité
        if ($stock->quantite_stock < $validated['quantite']) {
            return response()->json([
                'message' => 'Quantité en stock insuffisante pour cette vente.'
            ], 400);
        }
    
        // Si tout est bon ➔ Calculer le prix total
        $validated['prix_total'] = $validated['prix_unitaire'] * $validated['quantite'];
    
        // Créer la vente
        $vente = Vente::create($validated);
    
        // Décrémenter la quantité dans le stock
        $stock->quantite_stock -= $validated['quantite'];
        $stock->save();
    
        return [
            'message' => 'Vente enregistrée avec succès et stock mis à jour.',
            'vente' => $vente
        ];
    }
    

    // 🔁 Modifier une vente
    public function update(VenteFormRequest $request, int $id)
    {
        $validated = $request->validated();
        $vente = Vente::findOrFail($id);
    
        // Récupérer le stock associé à la vente
        $stock = Stock::find($vente->stock_id);
    
        if (!$stock) {
            return response()->json([
                'message' => 'Stock introuvable.'
            ], 404);
        }
    
        // Vérifier si la quantité modifiée dépasse le stock disponible
        $ancienneQuantite = $vente->quantite;
        $nouvelleQuantite = $validated['quantite'];
    
        // Si la nouvelle quantité est supérieure à l'ancienne, on ajoute la différence au stock
        if ($nouvelleQuantite > $ancienneQuantite) {
            $difference = $nouvelleQuantite - $ancienneQuantite;
            if ($stock->quantite_stock < $difference) {
                return response()->json([
                    'message' => 'Quantité insuffisante dans le stock pour cette mise à jour.'
                ], 400);
            }
            // Réduire la quantité de stock en fonction de la nouvelle quantité
            $stock->quantite_stock -= $difference;
        } 
        // Si la nouvelle quantité est inférieure à l'ancienne, on ajoute la différence au stock
        else if ($nouvelleQuantite < $ancienneQuantite) {
            $difference = $ancienneQuantite - $nouvelleQuantite;
            // Ajouter la différence au stock
            $stock->quantite_stock += $difference;
        }
    
        // Mettre à jour la vente
        $validated['prix_total'] = $validated['prix_unitaire'] * $nouvelleQuantite;
        $vente->update($validated);
    
        // Sauvegarder le stock mis à jour
        $stock->save();
    
        return response()->json([
            'message' => 'Vente mise à jour avec succès et stock réajusté.',
            'vente' => $vente
        ]);
    }
    

    // 🗑️ Supprimer une vente
    public function delete(int $id)
{
    $vente = Vente::findOrFail($id);

    // Récupérer le stock associé à cette vente
    $stock = Stock::find($vente->stock_id);

    if (!$stock) {
        return response()->json([
            'message' => 'Stock introuvable.'
        ], 404);
    }

    // Réajuster la quantité du stock après la suppression de la vente
    $stock->quantite_stock += $vente->quantite; // Ajoute la quantité de la vente supprimée au stock
    $stock->save();

    // Supprimer la vente
    $vente->delete();

    return response()->json([
        'message' => 'Vente supprimée et stock réajusté.'
    ]);
}

public function statistiquesMensuelles()
{
    $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

    $ventes = Vente::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as periode"),
            DB::raw("DATE_FORMAT(created_at, '%M') as mois"),
            DB::raw("SUM(prix_total) as total")
        )
        ->where('created_at', '>=', $sixMonthsAgo)
        ->groupBy('periode', 'mois')
        ->orderBy('periode')
        ->get()
        ->map(function ($item) {
            return [
                'mois' => $item->mois,
                'total' => $item->total,
            ];
        });

    return response()->json($ventes);
}

}


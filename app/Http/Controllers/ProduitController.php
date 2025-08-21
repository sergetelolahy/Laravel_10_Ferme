<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Http\Requests\ProduitFormRequest;

class ProduitController extends Controller
{
    public function index() {
        $produits = Produit::with('animal')->get(); // Assure-toi que la relation est bien nommée 'animal'
        return response()->json($produits);
    }

    public function create(ProduitFormRequest $request)
    {   
        $validated = $request->validated();
    
        // Créer le produit
        $produit = Produit::create($validated);
    
        // Ajouter ou mettre à jour le stock
        $this->ajouterAuStock($validated['type'], $validated['quantite'], $validated['prix']);
    
        return ['message' => 'Produit ajouté avec succès'];
    }
    
    private function ajouterAuStock($typeProduit, $quantite, $prix)
    {
        // Vérifier si un stock existe déjà pour ce type de produit
        // Ici, la colonne dans la table 'stock' est 'type_produit'
        $stock = Stock::where('type_produit', $typeProduit)->first();
    
        if (!$stock) {
            // Si le stock n'existe pas, on le crée
            Stock::create([
                'type_produit' => $typeProduit, // Utiliser 'type_produit' dans la table stock
                'quantite_stock' => $quantite,
                'prix' => $prix
            ]);
        } else {
            // Si le stock existe, on met à jour la quantité
            $stock->quantite_stock += $quantite;
            $stock->save();
        }
    }
    
    public function update(ProduitFormRequest $request, int $id)
    {
        $produit = Produit::findOrFail($id);
        $validated = $request->validated();
    
        $ancienneQuantite = $produit->quantite;
        $nouvelleQuantite = $validated['quantite'];
        $difference = $nouvelleQuantite - $ancienneQuantite;
    
        // Mettre à jour le produit
        $produit->update($validated);
    
        // Mettre à jour le stock
        $this->mettreAJourStockApresModification($validated['type'], $difference, $validated['prix']);
    
        return ['message' => 'Produit modifié et stock mis à jour avec succès'];
    }
    

    private function mettreAJourStockApresModification($typeProduit, $differenceQuantite, $nouveauPrix)
    {
        $stock = Stock::where('type_produit', $typeProduit)->first();

        if ($stock) {
            $stock->quantite_stock += $differenceQuantite;

            // Si le prix a changé, on peut aussi le mettre à jour
            $stock->prix = $nouveauPrix;

            // Si la quantité devient négative, on la met à 0 (ou gère comme tu veux)
            if ($stock->quantite_stock < 0) {
                $stock->quantite_stock = 0;
            }

            $stock->save();
        }
    }


    // public function delete (int $id)
    // {
    //     $produit = Produit::findOrFail($id);
    //     $produit = $produit->delete();

    //     return ['message' => 'delete succes'];
    // }
    public function delete(int $id)
    {
        // Trouver le produit
        $produit = Produit::findOrFail($id);
        
        // Récupérer le type de produit et la quantité
        $typeProduit = $produit->type; // Assure-toi que cette colonne existe dans ton modèle 'Produit'
        $quantiteProduit = $produit->quantite; // Assure-toi que cette colonne existe également
        
        // Supprimer le produit
        $produit->delete();

        // Mettre à jour le stock
        $this->mettreAJourStock($typeProduit, $quantiteProduit);

        return ['message' => 'Produit supprimé et stock mis à jour avec succès'];
    }

    private function mettreAJourStock($typeProduit, $quantiteProduit)
    {
        // Trouver le stock correspondant au type de produit
        $stock = Stock::where('type_produit', $typeProduit)->first();

        if ($stock) {
            // Si le stock existe, on décrémente la quantité
            $stock->quantite_stock -= $quantiteProduit;

            // Si la quantité devient inférieure ou égale à zéro, on peut supprimer le stock
            if ($stock->quantite_stock <= 0) {
                $stock->delete();
            } else {
                // Sinon, on met à jour la quantité dans le stock
                $stock->save();
            }
        }
    }


}

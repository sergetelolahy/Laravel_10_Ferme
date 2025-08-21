<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['type_produit', 'quantite_stock','prix'];

    // Fonction pour ajouter des produits au stock
    public function ajouterProduit(int $quantite)
    {
        $this->quantite_stock += $quantite;
        $this->save();
    }

    // Fonction pour vendre des produits et diminuer le stock
    public function vendreProduit(int $quantite)
    {
        if ($this->quantite_stock >= $quantite) {
            $this->quantite_stock -= $quantite;
            $this->save();
        } else {
            throw new \Exception('Stock insuffisant');
        }
    }

    public function vente()
    {
        return $this->hasMany(Vente::class, 'stock_id');
    }

}

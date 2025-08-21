<?php

namespace App\Models;

use App\Models\Soin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'espece',
        'race',
        'date_arrivee',
        'quantite'    
    ];

    public function soins()
    {
        return $this->hasMany(Soin::class);
    }

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
 
}


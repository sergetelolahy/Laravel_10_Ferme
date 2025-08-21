<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'type', 'quantite', 'prix', 'animal_id', 'date'];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}

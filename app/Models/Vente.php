<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_id',
        'client_id',
        'quantite',
        'prix_unitaire',
        'prix_total',
        'date_vente'
    ];

    // Une vente appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Une vente appartient à un stock
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}

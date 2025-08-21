<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'telephone'];

    public function vente()
    {
        return $this->hasMany(Vente::class, 'client_id');
    }
}

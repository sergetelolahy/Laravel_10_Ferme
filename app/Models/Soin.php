<?php

namespace App\Models;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Soin extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_soin',
        'observation', 
        'date_soin',
        'animal_id'  
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}

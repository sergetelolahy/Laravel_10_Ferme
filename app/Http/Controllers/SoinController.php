<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoinFormRequest;
use App\Models\Animal;
use App\Models\Soin;
use Illuminate\Http\Request;

class SoinController extends Controller
{
    public function index(){
        $Soins = Soin::get();
        return $Soins;
    }

    public function create(SoinFormRequest $request){
        $validated = $request->validated();
        $Soin = Soin::create($validated);

        return ['message' => $Soin];
    }

    public function update (SoinFormRequest $request, int $id)
    {   
        $validated = $request->validated();
        $Soin = Soin::findOrFail($id);
        $Soin = $Soin->update($validated);

        return ['update' => $Soin];
    }

    public function delete(int $id){
      $Soin = Soin::findOrFail($id);
      $Soin = $Soin->delete();
    }

    public function updateSoinByAnimal(Request $request, int $animalId)
{
    //Soin::where('animal_id', $animalId)->update($request->only(['type_soin', 'date_soin', 'observation']));
    $animal = Animal::Find($animalId);
   $Soin= $animal->soins->pluck('type_soin');
    return $Soin;
}

}

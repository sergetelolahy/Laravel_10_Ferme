<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalFormRequest;
use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index(){
        $Animals = Animal::get();
        return $Animals;
    }

    public function create(AnimalFormRequest $request){
        $validated = $request->validated();
        $Animal = Animal::create($validated);

        return ['message' => $Animal];
    }

    public function update (AnimalFormRequest $request, int $id)
    {   
        $validated = $request->validated();
        $Animal = Animal::findOrFail($id);
        $Animal = $Animal->update($validated);

        return ['update' => $Animal];
    }

    public function delete(int $id){
      $Animal = Animal::findOrFail($id);
      $Animal = $Animal->delete();
    }

    
}

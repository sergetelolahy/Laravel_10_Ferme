<?php


namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\ClientFormRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::get();
        return $clients;
    }

    public function create(ClientFormRequest $request)
    {
        $validated = $request->validated();
        $client = Client::create($validated);

        return ['message' => $client];
    }

    public function update(ClientFormRequest $request, int $id)
    {
        $validated = $request->validated();
        $client = Client::findOrFail($id);
        $client->update($validated);

        return ['update' => $client];
    }

    public function delete(int $id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return ['message' => 'Client supprimé avec succès'];
    }
}

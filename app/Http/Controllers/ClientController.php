<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;


class ClientController extends Controller
{
    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientRepository = new ClientRepository($this->client);

        /* Define os campos a serem exibidos nos "projetos" de cada cliente. */
        if ($request->has('attrs_projects')) {
            $attrs_projects = 'projects:id,'.$request->attrs_projects.',client_id';
            $clientRepository->selectRelatedRegistersAttributes($attrs_projects);
        } else {
            $clientRepository->selectRelatedRegistersAttributes('projects');
        }

        /* Define os filtros a serem aplicados nos campos para cada cliente. (cláusula WHERE) */
        if ($request->has('filters')) {
            $clientRepository->filter($request->filters);
        }

        /* Define os campos a serem exibidos nos clientes. */
        if ($request->has('attrs')) {
            $clientRepository->selectAttributes('id,'.$request->attrs);
        }

        return response()->json($clientRepository->getResult(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->client->rules(), $this->client->feedback());

        $client = $this->client->create($request->all());
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->client->with('projects')->find($id);
        if($client === null) {
            return response()->json(['erro' => 'Cliente não encontrado'], 404) ;
        }

        return response()->json($client, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $client = $this->client->find($id);
        if ( !$client ) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($client->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules, $client->feedback());
        } else {
            $request->validate($client->rules(), $client->feedback());
        }

        $client->update($request->all());
        return response()->json($client, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        if ( !$client ) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }
        $client->delete();
        return response()->json(['message' => 'Cliente removido com sucesso.'], 200);
    }
}

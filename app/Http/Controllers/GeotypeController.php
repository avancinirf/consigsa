<?php

namespace App\Http\Controllers;

use App\Models\Geotype;
use Illuminate\Http\Request;

class GeotypeController extends Controller
{
    public function __construct(Geotype $geotype) {
        $this->geotype = $geotype;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $geotypes = $this->geotype->all();
        return response()->json($geotypes, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->geotype->rules(), $this->geotype->feedback());

        $geotype = $this->geotype->create($request->all());
        return response()->json($geotype, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $geotype = $this->geotype->find($id);
        if ( !$geotype ) {
            return response()->json(['error' => 'Geotype não encontrado.'], 404);
        }
        return response()->json($geotype, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Geotype  $geotype
     * @return \Illuminate\Http\Response
     */
    public function edit(Geotype $geotype)
    {
        //
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
        $geotype = $this->geotype->find($id);
        if ( !$geotype ) {
            return response()->json(['error' => 'Geotype não encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($geotype->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules, $geotype->feedback());
        } else {
            $request->validate($geotype->rules(), $geotype->feedback());
        }

        $geotype->update($request->all());
        return response()->json($geotype, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $geotype = $this->geotype->find($id);
        if ( !$geotype ) {
            return response()->json(['error' => 'Geotype não encontrado.'], 404);
        }
        $geotype->delete();
        return response()->json(['message' => 'Geotype removido com sucesso.'], 200);
    }
}

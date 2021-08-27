<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(Project $project) {
        $this->project = $project;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Forma para retornar a lista limpa, apenas os projetos, sem os arquivos.
        //return response()->json($this->project->all(), 200);
        return response()->json($this->project->with('files')->get(), 200);
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
        $request->validate($this->project->rules(), $this->project->feedback());

        $project = $this->project->create($request->all());
        return response()->json($project, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = $this->project->with('files')->find($id);
        if($project === null) {
            return response()->json(['erro' => 'Projeto não encontrado'], 404) ;
        }

        return response()->json($project, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
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
        dd($request);
        $project = $this->project->find($id);
        if ( !$project ) {
            return response()->json(['error' => 'Projeto não encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($project->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules, $project->feedback());
        } else {
            $request->validate($project->rules(), $project->feedback());
        }

        $project->update($request->all());
        return response()->json($project, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = $this->project->find($id);
        if ( !$project ) {
            return response()->json(['error' => 'Projeto não encontrado.'], 404);
        }
        $project->delete();
        return response()->json(['message' => 'Projeto removido com sucesso.'], 200);
    }
}

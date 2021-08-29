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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $projects = [];

        /* Define os campos a serem exibidos nos "files" de cada projeto. */
        if ($request->has('attrs_files')) {
            $attrs_files = $request->attrs_files;
            $projects = $this->project->with('files:id,'.$attrs_files.',project_id');
        } else {
            $projects = $this->project->with('files');
        }

        /* Define os filtros a serem aplicados nos campos para cada projeto. (cláusula WHERE) */
        if ($request->has('filters')) {
            $filters = str_replace( '\\', '', $request->filter );
            $filters = explode( ';', $request->filters );

            foreach( $filters as $key => $filter ) {
                $f = explode(':', $filter);
                $projects = $projects->where($f[0], $f[1], $f[2]);
            }
        }

        /* Define os campos a serem exibidos nos projeto. */
        if ($request->has('attrs')) {
            $attrs = $request->attrs;
            $projects = $projects->selectRaw('id,'.$attrs)->get();
        } else {
            $projects = $projects->get();
        }

        return response()->json($projects, 200);

        /* Forma para retornar a lista limpa, apenas os projetos, com e sem os arquivos, mas sem filtrar os atributos e os valores */
        //return response()->json($this->project->all(), 200);
        //return response()->json($this->project->with('files')->get(), 200);
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

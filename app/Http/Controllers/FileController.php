<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct(File $file) {
        $this->file = $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $files = [];

        // Filter projects attributes
        if ($request->has('attrs_project')) {
            $attrs_project = $request->attrs_project;
            $files = $this->file->with('project:id,'.$attrs_project);
        } else {
            $files = $this->file->with('project');
        }

        if ($request->has('filters')) {
            $filters = str_replace( '\\', '', $request->filter );
            $filters = explode( ';', $request->filters );

            foreach( $filters as $key => $filter ) {
                $f = explode(':', $filter);
                $files = $files->where($f[0], $f[1], $f[2]);
            }
        }

        // Filter files attributes
        if ($request->has('attrs')) {
            $attrs = $request->attrs;
            $files = $files->selectRaw($attrs)->get();
        } else {
            $files = $files->get();
        }

        return response()->json($files, 200);
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
        $request->validate($this->file->rules(), $this->file->feedback());

        $archive     = $request->file('file');
        $folder_name = "/project_{$request->project_id}/files";
        $archive_urn = $archive->store($folder_name, 'local');

        $file = $this->file->create([
            'name'        => $request->name,
            'description' => $request->description,
            'file'        => $archive_urn,
            'project_id'  => $request->project_id
        ]);

        return response()->json($file, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = $this->file->with('project')->find($id);
        if ( !$file ) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }
        return response()->json($file, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
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
        $file = $this->file->find($id);
        if ( !$file ) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dynamicRules = [];

            foreach ($file->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules, $file->feedback());
        } else {
            $request->validate($file->rules(), $file->feedback());
        }

        if ($request->file('file')) {
            Storage::disk('local')->delete($file->file);
            $archive     = $request->file('file');
            $project_id  = $request->project_id ?? $file->project_id;
            $folder_name = "project_{$project_id}/files";
            $archive_urn = $archive->store($folder_name, 'local');
        } else {
            $archive_urn = $file->file;
        }

        $file->fill($request->all());
        $file->file = $archive_urn;
        $file->save();
        return response()->json($file, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = $this->file->find($id);

        if ( !$file ) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        Storage::disk('local')->delete($file->file);

        $file->delete();
        return response()->json(['message' => 'Arquivo removido com sucesso.'], 200);
    }
}

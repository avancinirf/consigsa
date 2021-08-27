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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Forma para retornar a lista limpa, apenas os arquivos, sem a redundância dos projetos.
        //return response()->json($this->file->all(), 200);
        return response()->json($this->file->with('project')->get(), 200);
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

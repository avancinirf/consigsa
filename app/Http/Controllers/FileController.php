<?php

namespace App\Http\Controllers;

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
        $files = $this->file->all();
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

        $file        = $request->file('file');
        $folder_name = "client_{$request->client_id}/project_{$request->project_id}/files";
        $file_urn    = $file->store($folder_name, 'local');

        $db_file = $this->file->create([
            'name'        => $request->name,
            'description' => $request->description,
            'file'        => $file_urn
        ]);

        //$file = $this->file->create($request->all());
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
        $file = $this->file->find($id);
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

        $file->update($request->all());
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
        $file->delete();
        return response()->json(['message' => 'Arquivo removido com sucesso.'], 200);
    }
}

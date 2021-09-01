<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Http\Request;
use App\Repositories\FileRepository;

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
        $fileRepository = new FileRepository($this->file);

        /* Define os campos a serem exibidos no "projeto" do arquivo. */
        if ($request->has('attrs_project')) {
            $attrs_project = 'project:id,'.$request->attrs_project;
            $fileRepository->selectRelatedRegistersAttributes($attrs_project);
        } else {
            $fileRepository->selectRelatedRegistersAttributes('project');
        }

        /* Define os filtros a serem aplicados nos campos para cada arquivo. (cláusula WHERE) */
        if ($request->has('filters')) {
            $fileRepository->filter($request->filters);
        }

        /* Define os campos a serem exibidos nos arquivos. */
        if ($request->has('attrs')) {
            $fileRepository->selectAttributes('id,'.$request->attrs.',project_id');
        }

        return response()->json($fileRepository->getResult(), 200);

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

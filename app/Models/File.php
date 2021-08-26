<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'file'];

    public function rules() {
        $id = $this->id ?? 'NULL';
        return [
            'name'        => "required|unique:files,name,{$id}|min:5|max:50",
            'description' => "max:1000",
            'file'        => "required|file|mimes:pdf,xls,xlsx,doc,docx,ppt,jpg,jpeg,kml,kmz,zip,rar",
            'client_id'   => "required",
            'project_id'  => "required"
        ];
    }

    public function feedback() {
        return [
            'name.required'        => 'Campo nome é obrigatório.',
            'name.unique'     => 'Nome informado já existe.',
            'name.min'        => 'O nome possui no mínimo 3 de caracteres.',
            'name.max'        => 'O nome possui no máximo 50 de caracteres.',
            'description.max' => 'A descrição possui no máximo 1000 de caracteres.',
            'file.required'   => 'O arquivo é obrigatório',
            'file.mimes'      => 'O arquivo deve ser do tipo pdf|xls|xlsx|doc|docx|ppt|jpg|jpeg|kml|kmz|zip|rar'
        ];
    }
}

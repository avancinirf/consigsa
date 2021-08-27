<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'started_at', 'finished_at'];
    //protected $fillable = ['client_id', 'name', 'description', 'started_at', 'finished_at'];

    public function rules() {
        $id = $this->id ?? 'NULL';
        return [
            //'project_id'  => 'exists:project,id',
            'name'        => "required|unique:files,name,{$id}|min:5|max:50",
            'description' => "max:1000",
            'started_at'  => "date",
            'finished_at' => "date",
            //'client_id'   => "required"
        ];
    }

    public function feedback() {
        return [
            'name.required'   => 'Campo nome é obrigatório.',
            'name.unique'     => 'Nome informado já existe.',
            'name.min'        => 'O nome possui no mínimo 3 de caracteres.',
            'name.max'        => 'O nome possui no máximo 50 de caracteres.',
            'description.max' => 'A descrição possui no máximo 1000 de caracteres.',
            'date'            => 'Data inválida.'
        ];
    }

    public function files() {
        return $this->hasMany('App\Models\File');
    }
}

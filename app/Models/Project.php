<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'name', 'description', 'started_at', 'finished_at'];

    public function rules() {
        $id = $this->id ?? 'NULL';
        return [
            'client_id'   => 'exists:client,id',
            'name'        => "required|unique:projects,name,{$id}|min:5|max:50",
            'description' => 'max:1000',
            'started_at'  => 'date',
            'finished_at' => 'date',
            'client_id'   => 'required'
        ];
    }

    public function feedback() {
        return [
            'client_id.required' => 'É obrigatório vincular um cliente ao projeto.',
            'name.required'      => 'Campo nome é obrigatório.',
            'name.unique'        => 'Nome informado já existe.',
            'name.min'           => 'O nome possui no mínimo 3 de caracteres.',
            'name.max'           => 'O nome possui no máximo 50 de caracteres.',
            'description.max'    => 'A descrição possui no máximo 1000 de caracteres.',
            'date'               => 'Data inválida.'
        ];
    }

    public function client() {
        return $this->belongsTo('App\Models\Client');
    }

    public function files() {
        return $this->hasMany('App\Models\File');
    }

}

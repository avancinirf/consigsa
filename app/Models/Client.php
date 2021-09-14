<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'email', 'is_admin', 'is_available'];

    public function rules() {
        $id = $this->id ?? 'NULL';
        return [
            'name'         => "required|unique:clients,name,{$id}|min:5|max:50",
            'email'        => "required|unique:clients,email,{$id}|max:100"
        ];
    }

    public function feedback() {
        return [
            'name.required'   => 'Campo nome é obrigatório.',
            'name.unique'     => 'Nome informado já existe.',
            'email.required'  => 'Campo e-mail é obrigatório.',
            'email.unique'    => 'E-mail informado já existe.',
            'name.min'        => 'O nome possui no mínimo 3 de caracteres.',
            'name.max'        => 'O nome possui no máximo 50 de caracteres.',
            'email.max'       => 'O nome possui no máximo 100 de caracteres.'
        ];
    }

    public function projects() {
        return $this->hasMany('App\Models\Project');
    }

}

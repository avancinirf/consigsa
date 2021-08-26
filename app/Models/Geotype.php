<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geotype extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function rules() {
        $id = $this->id ?? 'NULL';
        return [
            'name' => "required|unique:geotypes,name,{$id}|min:5|max:20"
        ];
    }

    public function feedback() {
        return [
            'required'    => 'Campo nome é obrigatório.',
            'name.unique' => 'Nome informado já existe.',
            'name.min'    => 'Campo nome possui no mínimo 3 de caracteres.',
            'name.max'    => 'Campo nome possui no máximo 20 de caracteres.'
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules() {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required|min:7|max:10', Rule::unique('carros')->ignore($this->id),
            'disponivel' => 'required|boolean',
            'km' => 'required|integer|min:0|max:999999'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo é obrigatório',
            'modelo_id.exists' => 'O modelo do carro não existe',
            'placa.min' => 'A placa deve conter no minimo 7 caracteres',
            'placa.max' => 'A placa deve conter no máximo 10 caracteres',
            'placa.unique' => 'A placa já está sendo usada por outro carro',
            'disponivel.boolean' => 'O valor deve ser booleano',
            'km.integer' => 'A kilometragem do carro deve ser um valor inteiro',
            'km.min' => 'A kilometragem deve ser entre 0 e 999.999',
            'km.max' => 'A kilometragem deve ser entre 0 e 999.999'
        ];
    }

    public function modelo() {
        return $this->belongsTo(Modelo::class);
    }
}

<?php

namespace App\Models;

use App\Models\Carro;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|min:3', Rule::unique('name')->ignore($this->id),
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,4',
            'lugares' => 'required|integer|digits_between:1,5',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean'
        ];
    }

    public function feedback() {
        return [
            'marca_id.exists' => 'Insira uma marca existente',
            'required' => 'Esse campo é requirido',
            'nome.min' => 'O modelo deve conter no minimo 3 caracteres',
            'nome.unique' => 'O modelo já existe',
            'imagem.file' => 'O conteudo enviado deve ser um arquivo',
            'imagem.mimes' => 'Os tipos aceitados são => png, jpeg, jpg',
            'numero_portas.integer' => 'O número de portas deve ser um inteiro',
            'numero_portas.digits_between' => 'O modelo pode conter de 1 a 4 portas',
            'lugares.integer' => 'O número de lugares deve ser um inteiro',
            'lugares.digits_between' => 'O modelo pode conter de 1 a 5 lugares',
            'boolean' => 'O valor inserido não é booleano [true, false, 1, 0, "1", "0"]'
        ];
    }

    public function marca() {
        return $this->belongsTo(Marca::class);
    }

    public function carros() {
        return $this->hasMany(Carro::class);
    }
}

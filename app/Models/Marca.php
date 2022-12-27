<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        $user = Marca::find($this->id);
        return [
            'nome' => (!is_null($user) ? 'required|min:3|unique:marcas,nome,'.$user->id : 'required|min:3|unique:marcas,nome'),
            // Rule::unique('marcas')->ignore($this->id),
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'A marca já existe',
            'nome.min' => 'O nome da marca deve ter no minimo 3 caracteres',
            'imagem.file' => 'O arquivo deve ser uma imagem',
            'imagem.mimes' => 'O arquivo deve ser do tipo PNG'
        ];
    }

    public function modelos() {
        return $this->hasMany(Modelo::class);
    }
}

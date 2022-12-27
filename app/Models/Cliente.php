<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function rules () {
        return [
            'nome' => 'required|max:30'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo é obrigatório',
            'nome.max' => 'O nome pode conter até no maximo 30 caracteres'
        ];
    }
}

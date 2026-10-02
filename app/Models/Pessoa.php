<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{

    protected $fillable = ['nome', 'cpf', 'tipo, telefone', 'email'];
    
    /** @use HasFactory<\Database\Factories\PessoaFactory> */
    use HasFactory;
}

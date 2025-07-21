<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'itens',
        'subtotal',
        'frete',
        'total',
        'status',
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'estado'
    ];
    protected $casts = [
        'itens' => 'array',
    ];
}

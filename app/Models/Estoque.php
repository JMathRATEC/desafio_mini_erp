<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    protected $table = 'estoque';
    protected $fillable = ['produto_id', 'variacao', 'quantidade'];
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

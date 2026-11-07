<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'produto_id',
        'quantidade',
        'valor_unitario',
        'fornecedor'
    ];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
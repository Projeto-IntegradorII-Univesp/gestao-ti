<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';
    public $timestamps = false; // Tabela legada 

    protected $fillable = [
        'nome',
        'categoria',
        'estoque_atual',
        'estoque_minimo',
        'imagem',
        'ativo'
    ];

    // Mapeamento virtual para saber $produto->quantidade
    public function getQuantidadeAttribute()
    {
        return $this->attributes['estoque_atual'] ?? 0;
    }

    public function setQuantidadeAttribute($value)
    {
        $this->attributes['estoque_atual'] = $value;
    }

    // Mapeamento virtual para saber $produto->status
    public function getStatusAttribute()
    {
        return $this->attributes['ativo'] ?? 1;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['ativo'] = $value;
    }
}
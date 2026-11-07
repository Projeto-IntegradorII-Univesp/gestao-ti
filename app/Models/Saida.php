<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Saida extends Model
{
    protected $table = 'saidas';
    public $timestamps = false; // Tabela legada usa data_saida

    protected $fillable = [
        'produto_id',
        'quantidade',
        'destino_setor',
        'responsavel',
        'observacao',
        'data_saida'
    ];

    // Mapeamento virtual para $saida->created_at funcionar na view
    public function getCreatedAtAttribute()
    {
        return isset($this->attributes['data_saida']) 
            ? \Carbon\Carbon::parse($this->attributes['data_saida']) 
            : \Carbon\Carbon::now();
    }

    // Mapeamento virtual para $saida->destinatario funcionar na view
    public function getDestinatarioAttribute()
    {
        return $this->attributes['destino_setor'] ?? '';
    }

    public function setDestinatarioAttribute($value)
    {
        $this->attributes['destino_setor'] = $value;
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}
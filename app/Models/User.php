<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    public $timestamps = false; // Tabela legada do MySQL

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'nivel_acesso',
        'cargo',
        'foto'
    ];

    protected $hidden = [
        'senha',
    ];

    // Indica qual campo guarda a senha no banco MySQL
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Acessores virtuais para manter compatibilidade no Laravel
    public function getNameAttribute()
    {
        return $this->attributes['nome'] ?? '';
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nome'] = $value;
    }

    public function getTipoAttribute()
    {
        return ($this->attributes['nivel_acesso'] ?? 'funcionario') === 'admin' ? 'admin' : 'comum';
    }

    public function setTipoAttribute($value)
    {
        $this->attributes['nivel_acesso'] = ($value === 'admin') ? 'admin' : 'funcionario';
    }
}
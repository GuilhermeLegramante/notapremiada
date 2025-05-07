<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons';

    protected $fillable = [
        'user_id',
        'chave_acesso',
        'valor_total',
        'fornecedor',
        'data_cadastro',
        'observacao',
    ];

    protected $casts = [
        'data_cadastro' => 'date',
        'valor_total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function numerosSorteio()
    {
        return $this->hasMany(NumeroSorteio::class);
    }

    protected static function booted()
    {
        static::created(function (Cupom $cupom) {
            $valorNota = $cupom->valor_total;
            $user = $cupom->user;

            $acumulado = $user->saldo_sorteio + $valorNota;

            $quantidadeNumeros = floor($acumulado / env('VALOR_NUMERO_SORTEIO', 200));
            $novoSaldo = fmod($acumulado, env('VALOR_NUMERO_SORTEIO', 200));

            for ($i = 0; $i < $quantidadeNumeros; $i++) {
                $numero = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $cupom->numerosSorteio()->create(['numero' => $numero]);
            }

            $user->saldo_sorteio = $novoSaldo;
            $user->save();
        });
    }
}

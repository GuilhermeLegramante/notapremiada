<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroSorteio extends Model
{
    use HasFactory;

    protected $fillable = [
        'cupom_id',
        'numero',
        'codigo_continuo',
    ];

    public function cupom()
    {
        return $this->belongsTo(Cupom::class);
    }

    public static function getProximoCodigoDisponivel(): int
    {
        $codigos = self::orderBy('codigo_continuo')->pluck('codigo_continuo')->filter()->values()->all();
        $esperado = 1;

        foreach ($codigos as $codigo) {
            if ($codigo != $esperado) {
                return $esperado; // achou furo
            }
            $esperado++;
        }

        return $esperado; // se não tiver furo, usa o próximo
    }
}

<?php

namespace App\Models;

use App\Notifications\NumerosSorteioGeradosNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

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
        'validado',
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

            if (!$user || $valorNota <= 0) {
                Log::warning("Cupom #{$cupom->id} criado sem usuário válido ou com valor inválido.");
                return;
            }

            DB::transaction(function () use ($cupom, $user, $valorNota) {
                $valorPorNumero = env('VALOR_NUMERO_SORTEIO', 200);

                $acumulado = $user->saldo_sorteio + $valorNota;
                $quantidadeNumeros = floor($acumulado / $valorPorNumero);
                $novoSaldo = fmod($acumulado, $valorPorNumero);

                $numerosGerados = [];

                for ($i = 0; $i < $quantidadeNumeros; $i++) {
                    do {
                        // Gera número de 9 dígitos (zero à esquerda se necessário)
                        $numero = str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);

                        // Evita duplicidade global no banco
                        $existe = NumeroSorteio::where('numero', $numero)->exists();
                    } while ($existe);

                    $numeroSorteio = $cupom->numerosSorteio()->create(['numero' => $numero]);
                    $numerosGerados[] = $numeroSorteio->numero;
                }

                $user->saldo_sorteio = $novoSaldo;
                $user->save();

                Log::info("Gerados {$quantidadeNumeros} número(s) para o cupom #{$cupom->id} do usuário #{$user->id}");

                if ($quantidadeNumeros > 0 && $adminEmail = env('ADMIN_EMAIL')) {
                    Notification::route('mail', $adminEmail)
                        ->notify(new NumerosSorteioGeradosNotification($cupom, $quantidadeNumeros, $numerosGerados));
                }
            });
        });
    }
}

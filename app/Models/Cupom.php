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

            if (!$user) {
                return;
            }

            DB::transaction(function () use ($cupom, $user, $valorNota) {
                $acumulado = $user->saldo_sorteio + $valorNota;
                $valorPorNumero = env('VALOR_NUMERO_SORTEIO', 200);

                $quantidadeNumeros = floor($acumulado / $valorPorNumero);
                $novoSaldo = fmod($acumulado, $valorPorNumero);

                $numerosGerados = [];

                for ($i = 0; $i < $quantidadeNumeros; $i++) {
                    do {
                        $numero = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                        $existe = $cupom->numerosSorteio()->where('numero', $numero)->exists();
                    } while ($existe);

                    $numeroSorteio = $cupom->numerosSorteio()->create(['numero' => $numero]);
                    $numerosGerados[] = $numeroSorteio->id;
                }

                $user->saldo_sorteio = $novoSaldo;
                $user->save();

                Log::info("Gerados {$quantidadeNumeros} números para o cupom #{$cupom->id} do usuário #{$user->id}.");

                if ($quantidadeNumeros > 0) {
                    $adminEmail = env('ADMIN_EMAIL', 'guilhermelegramante@gmail.com');

                    if ($adminEmail) {
                        Notification::route('mail', $adminEmail)
                            ->notify(new NumerosSorteioGeradosNotification($cupom, $quantidadeNumeros, $numerosGerados));
                    }
                }
            });
        });
    }
}

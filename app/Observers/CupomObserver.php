<?php

namespace App\Observers;

use App\Models\Cupom;

class CupomObserver
{
    /**
     * Handle the Cupom "created" event.
     */
    public function created(Cupom $cupom): void
    {
        //
    }

    /**
     * Handle the Cupom "updated" event.
     */
    public function updated(Cupom $cupom): void
    {
        //
    }

    /**
     * Handle the Cupom "deleted" event.
     */
    public function deleted(Cupom $cupom): void
    {
        //
    }

    /**
     * Handle the Cupom "restored" event.
     */
    public function restored(Cupom $cupom): void
    {
        //
    }

    /**
     * Handle the Cupom "force deleted" event.
     */
    public function forceDeleted(Cupom $cupom): void
    {
        //
    }

    public function deleting(Cupom $cupom)
    {
        // Supondo que o cupom tenha relação com usuário e valor
        $user = $cupom->user;

        if ($user) {
            $novoSaldo = $user->saldo_sorteio - $cupom->valor_total;
            $user->saldo_sorteio = max($novoSaldo, 0); // Garante que o saldo nunca seja negativo

            $user->save();
        }
    }
}

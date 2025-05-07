<?php

namespace App\Filament\User\Widgets;

use App\Models\Cupom;
use App\Models\NumeroSorteio;
use App\Models\SorteioNumero;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserBalanceWidget extends Widget
{
    protected static string $view = 'filament.user.widgets.user-balance-widget';

    protected int|string|array $columnSpan = 'full';

    public function getSaldoEQuantidade(): array
    {
        $user = Auth::user();

        // Soma dos valores dos cupons do usuário
        $total = Cupom::where('user_id', $user->id)->sum('valor_total');

        // Pega IDs dos cupons do usuário
        $cuponsIds = Cupom::where('user_id', $user->id)->pluck('id');

        // Conta os números associados a esses cupons
        $quantidadeNumeros = NumeroSorteio::whereIn('cupom_id', $cuponsIds)->count();

        // Calcula o saldo restante
        $valorJaConvertido = $quantidadeNumeros * 200;
        $saldo = max(0, $total - $valorJaConvertido);

        return [
            'saldo' => $saldo,
            'quantidadeNumeros' => $quantidadeNumeros,
        ];
    }


    public function render(): \Illuminate\View\View
    {
        return view(static::$view, $this->getSaldoEQuantidade());
    }
}

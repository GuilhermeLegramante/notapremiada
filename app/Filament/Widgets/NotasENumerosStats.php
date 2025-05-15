<?php

namespace App\Filament\Widgets;

use App\Models\Cupom;
use App\Models\NumeroSorteio;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class NotasENumerosStats extends BaseWidget
{
    public function getSaldo()
    {
        $user = Auth::user();

        // Soma dos valores dos cupons do usuário
        $total = (float) Cupom::where('user_id', $user->id)->sum('valor_total');

        // Pega IDs dos cupons do usuário
        $cuponsIds = Cupom::where('user_id', $user->id)->pluck('id');

        // Conta os números associados a esses cupons
        $quantidadeNumeros = NumeroSorteio::whereIn('cupom_id', $cuponsIds)->count();

        // Calcula o saldo restante
        $valorJaConvertido = $quantidadeNumeros * 200;

        $saldo = max(0, $total - $valorJaConvertido);

        return $saldo;
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdmin = auth()->check() && auth()->user()->admin;

        $cuponsCount = $isAdmin
            ? Cupom::count()
            : Cupom::where('user_id', $user->id)->count();

        $numeroSorteiosCount = $isAdmin
            ? NumeroSorteio::count()
            : NumeroSorteio::join('cupons', 'cupons.id', '=', 'numero_sorteios.cupom_id')
            ->where('cupons.user_id', $user->id)
            ->count();

        return [
            Stat::make('Cupons Cadastrados', $cuponsCount)
                ->description('Total acumulado')
                ->color('primary')
                ->icon('heroicon-o-ticket'),

            Stat::make(
                $isAdmin ? 'Valor Total' : 'Saldo para Sorteio',
                'R$ ' . number_format(
                    $isAdmin ? Cupom::sum('valor_total') : $user->saldo_sorteio,
                    2,
                    ',',
                    '.'
                )
            )
                ->description($isAdmin
                    ? 'Soma de todas as notas lançadas'
                    : 'Soma acumulada para o próximo sorteio')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),

            Stat::make('Números para Sorteio', $numeroSorteiosCount)
                ->description('Total de números gerados')
                ->color('info')
                ->icon('heroicon-o-hashtag'),
        ];
    }
}

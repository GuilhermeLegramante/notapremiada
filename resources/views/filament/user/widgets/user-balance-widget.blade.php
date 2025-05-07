<x-filament::widget>
    <x-filament::card>
        <div class="text-xl font-bold">Saldo para Sorteio</div>
        <div class="mt-2">
            <p><strong>Saldo acumulado:</strong> R$ {{ number_format($saldo, 2, ',', '.') }}</p>
            <p><strong>Números gerados:</strong> {{ $quantidadeNumeros }}</p>
        </div>
    </x-filament::card>
</x-filament::widget>

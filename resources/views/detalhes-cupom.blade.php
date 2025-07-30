<x-filament::card>
    <h2 class="text-lg font-medium text-gray-900 mb-4">Cupom #{{ $record->id }}</h2>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <dt class="text-sm font-medium text-gray-500">Chave de Acesso</dt>
            <dd class="text-sm text-gray-900">{{ $record->chave_acesso }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Valor Total</dt>
            <dd class="text-sm text-gray-900">R$ {{ number_format($record->valor_total, 2, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Fornecedor</dt>
            <dd class="text-sm text-gray-900">{{ $record->fornecedor }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Data de Emissão</dt>
            <dd class="text-sm text-gray-900">{{ optional($record->data_emissao)->format('d/m/Y') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Usuário</dt>
            <dd class="text-sm text-gray-900">{{ $record->user?->name }}</dd>
        </div>
        <div class="col-span-2">
            <dt class="text-sm font-medium text-gray-500">Observação</dt>
            <dd class="text-sm text-gray-900">{{ $record->observacao }}</dd>
        </div>
        @if ($record->arquivo)
            <div class="col-span-2">
                <dt class="text-sm font-medium text-gray-500">Arquivo</dt>
                <dd class="text-sm text-gray-900">
                    <a href="{{ Storage::disk('public')->url($record->arquivo) }}" target="_blank"
                        class="text-blue-600 underline">
                        Ver Arquivo
                    </a>
                </dd>
            </div>
        @endif
        @if ($record->numerosSorteio->isNotEmpty())
            <div class="col-span-2">
                <dt class="text-sm font-medium text-gray-500">Números p/ Sorteio</dt>
                <dd class="text-sm text-gray-900 flex flex-wrap gap-2 mt-1">
                    @foreach ($record->numerosSorteio as $numero)
                        <span
                            class="inline-flex items-center rounded-md bg-green-100 px-2 py-0.5 text-sm font-medium text-green-800">
                            {{ str_pad($numero->codigo_continuo, 6, '0', STR_PAD_LEFT) }}
                        </span>
                    @endforeach
                </dd>
            </div>
        @endif
    </dl>
</x-filament::card>

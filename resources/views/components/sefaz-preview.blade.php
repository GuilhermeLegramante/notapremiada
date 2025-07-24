@if ($getState())
    <div class="mt-4 space-y-2">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded text-sm">
            <p><strong>Aviso:</strong> Abaixo está apenas uma <u>visualização</u> da nota fiscal no site da SEFAZ. Este
                sistema <strong>não consegue obter automaticamente os dados</strong> da nota apenas com a chave de
                acesso.</p>

            <p class="mt-2">
                Se você deseja preencher os dados automaticamente (valor, fornecedor, data, etc), utilize a
                <strong>opção de leitura por QR Code</strong> acima.
            </p>

            <p class="mt-2">
                É <strong>responsabilidade do usuário</strong> informar corretamente os dados da nota fiscal com base
                nas informações exibidas abaixo.
            </p>
        </div>

        @php
            $chave = $getState();
            $modelo = substr($chave, 20, 2); // posição 21-22
            $url =
                $modelo === '55'
                    ? 'https://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx'
                    : 'https://www.sefaz.rs.gov.br/NFE/NFE-NFC.aspx?chaveNFe=' . $chave;
        @endphp

        @if ($modelo === '55')
            <div class="border rounded shadow p-4 text-sm text-gray-700 bg-yellow-50">
                ⚠️ A consulta de NF-e (modelo 55) não pode ser exibida dentro do sistema.
                Clique no link abaixo para abrir a nota diretamente no site da Receita:
            </div>
        @else
            <div class="border rounded shadow">
                <iframe src="{{ $url }}" width="100%" height="400" style="border: none;"></iframe>
            </div>
        @endif

        <div>
            <a href="{{ $url }}" target="_blank" class="text-blue-600 underline hover:text-blue-800 text-sm">
                🔗 Abrir em nova aba
            </a>
        </div>
    </div>
@else
    <p class="text-sm text-gray-500">Insira a chave de acesso para visualizar a nota fiscal.</p>
@endif

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotaFiscalService;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index(Request $request)
    {
        $cupons = $request->user()->cupons()->with('numerosSorteio')->latest()->get();
        return response()->json($cupons);
    }

    public function store(Request $request)
    {
        // Validando a URL completa do QR Code
        $data = $request->validate([
            'qr_code' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Extraindo a chave de acesso da URL
        $chave = $this->extrairChaveAcesso($data['qr_code']);

        if (!$chave) {
            return response()->json([
                'message' => 'Chave de acesso inválida. Não foi possível extrair a chave da URL do QR Code.'
            ], 400);
        }

        // Verificar se a chave já foi cadastrada para este usuário
        $existingCupom = $request->user()->cupons()->where('chave_acesso', $chave)->first();

        if ($existingCupom) {
            return response()->json([
                'message' => 'Esta chave de acesso já foi cadastrada.'
            ], 400);
        }

        // Agora você pode continuar com a validação e a criação do cupom com a chave extraída
        $dados = NotaFiscalService::getDataFromQrCode($chave);

        if (!$dados) {
            return response()->json([
                'message' => 'Chave de acesso inválida. Não foi possível obter os dados da nota fiscal.'
            ], 400);
        }

        // Validação da cidade
        if (! $this->cidadeValida($dados['cidade'])) {
            return response()->json([
                'message' => 'A cidade não faz parte deste sorteio.'
            ], 400);
        }

        // Verifica se o valor total é válido
        if ($dados['total'] <= 0) {
            return response()->json([
                'message' => 'Valor total inválido. O valor da nota fiscal deve ser maior que 0.'
            ], 400);
        }

        // Criação do cupom
        $user = User::find($data['user_id']);
        if (!$user) {
            return response()->json([
                'message' => 'Usuário não encontrado.'
            ], 404);
        }

        $cupom = $user->cupons()->create([
            'chave_acesso' => $chave,
            'valor_total' => $dados['total'],
            'fornecedor' => $dados['empresa_nome'],
            'data_emissao' => $dados['data_emissao'],
            'data_cadastro' => now(),
            'validado' => true, // Defina como verdadeiro após validação
        ]);

        // Atualizar saldo do sorteio
        $acumulado = $user->saldo_sorteio + $dados['total'];
        $valorPorNumero = env('VALOR_NUMERO_SORTEIO', 200);
        $quantidadeNumeros = floor($acumulado / $valorPorNumero);
        $novoSaldo = fmod($acumulado, $valorPorNumero);

        // Gerar os números do sorteio
        for ($i = 0; $i < $quantidadeNumeros; $i++) {
            $numero = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $cupom->numerosSorteio()->create(['numero' => $numero]);
        }

        // Atualizar saldo do usuário
        $user->update(['saldo_sorteio' => $novoSaldo]);

        return response()->json([
            'message' => 'Cupom cadastrado com sucesso!',
            'cupom' => $cupom->load('numerosSorteio'),
        ], 201);
    }

    private function cidadeValida(string $cidade): bool
    {
        return mb_strtoupper($cidade) == mb_strtoupper(env('CIDADE_SORTEIO', 'CACEQUI'));
    }

    private function extrairChaveAcesso($url)
    {
        // Expressão regular para capturar a chave de acesso (que aparece após "p=")
        preg_match('/p=([0-9]{44})/', $url, $matches);

        // Retorna a chave de acesso, se encontrada
        return $matches[1] ?? null;
    }
}

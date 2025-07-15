<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Cupom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }

    public function atualizarPerfil(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return response()->json($user);
    }

    public function saldo(Request $request)
    {
        // Recupera o usuário autenticado via token Sanctum
        $user = $request->user();

        // Retorna o campo saldo_sorteio
        return response()->json([
            'saldo' => $user->saldo_sorteio ?? 0.0
        ]);
    }

    public function store(Request $request)
    {
        // Formata o CPF para aplicar a máscara
        $cpfFormatado = $this->formatarCpf($request->cpf);

        // Verifica se já existe usuário com esse CPF formatado
        if (User::where('cpf', $cpfFormatado)->exists()) {
            return response()->json([
                'errors' => ['cpf' => ['CPF já cadastrado.']],
            ], 422);
        }

        // Validação dos demais campos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'birth_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Criando o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $this->formatarCpf($request->cpf),
            'telefone' => $request->phone,
            'data_nascimento' => $request->birth_date,
        ]);

        // Gerando o token para o novo usuário
        $token = $user->createToken('NotaPremiadaApp')->plainTextToken;

        // Retornando resposta
        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Função para aplicar a máscara ao CPF
    private function formatarCpf($cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf); // Remove tudo que não é número
        return vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($cpf));
    }
}

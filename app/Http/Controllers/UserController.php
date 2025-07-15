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
        $cpf = preg_replace('/\D/', '', $request->cpf); // Garante que só números

        if (!$this->cpfEhValido($cpf)) {
            return response()->json([
                'errors' => ['cpf' => ['CPF inválido.']],
            ], 422);
        }

        $cpfFormatado = $this->formatarCpf($cpf);

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

        // Criação do usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $cpfFormatado,
            'telefone' => $request->phone,
            'data_nascimento' => $request->birth_date,
        ]);

        // Token
        $token = $user->createToken('NotaPremiadaApp')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Aplica máscara no CPF
    private function formatarCpf($cpf)
    {
        return vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($cpf));
    }

    // Verifica se o CPF é matematicamente válido
    private function cpfEhValido($cpf)
    {
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}

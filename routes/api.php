<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\NumeroSorteioController;
use App\Http\Controllers\SorteioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui é onde você pode registrar as rotas de API para sua aplicação.
| Essas rotas são carregadas pelo RouteServiceProvider dentro de um grupo
| que é atribuído ao middleware "api".
|
*/

// Rotas públicas (sem autenticação)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/cadastro', [AuthController::class, 'register']);

// Rotas protegidas por autenticação Sanctum F
Route::middleware('auth:sanctum')->group(function () {
    // Perfil do usuário
    Route::get('/perfil', [UserController::class, 'perfil']);
    Route::put('/perfil', [UserController::class, 'atualizarPerfil']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cupons
    Route::get('/cupons', [CupomController::class, 'index']);
    Route::post('/cupons', [CupomController::class, 'store']);

    // Números de sorteio do usuário
    Route::get('/numeros', [NumeroSorteioController::class, 'meusNumeros']);

    Route::get('/saldo', [UserController::class, 'saldo']);

    Route::post('register', [UserController::class, 'store']);

    // Sorteios realizados
    // Route::get('/sorteios', [SorteioController::class, 'index']);
});

<?php

use App\Filament\Pages\Register;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/notapremiada/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/notapremiada/public/livewire/update', $handle);
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::redirect('/notapremiada/public/admin/login', '/notapremiada/public/admin/login')->name('login');

// Route::get('/', function () {
//     return redirect(route('filament.admin.pages.dashboard'));
// });
Route::get('/', [LandingPageController::class, 'index']);


Route::get('/user/register', Register::class)->name('filament.user.auth.register');


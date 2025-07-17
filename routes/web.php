<?php

use App\Filament\Pages\Register;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Mail;
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
// Route::redirect('/admin/login', '/admin/login')->name('login');


Route::get('/teste', function () {
    // Pega a parte da query da URL
    $query = parse_url('https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p=43250792665611040632651020002398741413562237|2|1|1|D24D2FEF248AE97F1DEB9A0D266DDC449BFE9F98', PHP_URL_QUERY);

    // Extrai tudo após "p="
    parse_str($query, $params);
    $p = $params['p'] ?? null;

    dd($p);
});

Route::view('/politica-de-privacidade', 'terms')->name('politica.privacidade');

Route::get('/', [LandingPageController::class, 'index']);


Route::get('/user/register', Register::class)->name('filament.user.auth.register');

Route::view('/exclusao-de-dados', 'exclusao-dados')->name('exclusao.dados');

Route::post('/exclusao-de-dados/enviar', function (\Illuminate\Http\Request $request) {
    // Aqui você pode armazenar no banco, enviar e-mail, etc.
    // Exemplo: envio de e-mail (se configurado)
    Mail::raw("Solicitação de exclusão de dados:\n\n" . print_r($request->all(), true), function ($message) use ($request) {
        $message->to('guilhermelegramante@gmail.com')
            ->subject('Solicitação de Exclusão de Dados - Nota Premiada');
    });

    return back()->with('success', 'Sua solicitação foi enviada com sucesso.');
})->name('exclusao.enviar');

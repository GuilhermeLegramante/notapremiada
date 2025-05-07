<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        // Se não estiver logado, deixa passar para a página de login
        if (!auth()->check()) {
            return $next($request);
        }

        // Se estiver logado e for admin, permite
        if (auth()->user()->admin) {
            return $next($request);
        }

        // Se estiver logado, mas não for admin, desloga e redireciona
        auth()->logout();
        return redirect(route('filament.admin.auth.login'));
    }
}

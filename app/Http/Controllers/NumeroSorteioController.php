<?php

namespace App\Http\Controllers;

use App\Models\NumeroSorteio;
use Illuminate\Http\Request;

class NumeroSorteioController extends Controller
{
    public function meusNumeros(Request $request)
    {
        $numeros = NumeroSorteio::whereHas('cupom', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->get();

        return response()->json($numeros);
    }
}

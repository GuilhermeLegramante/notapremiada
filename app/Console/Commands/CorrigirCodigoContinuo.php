<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NumeroSorteio;
use Illuminate\Support\Facades\DB;

class CorrigirCodigoContinuo extends Command
{
    protected $signature = 'numeros:reparar-codigo-continuo';
    protected $description = 'Preenche ou corrige a coluna codigo_continuo na tabela numero_sorteios';

    public function handle()
    {
        $this->info('Corrigindo codigo_continuo...');

        DB::transaction(function () {
            // Pega todos os registros ordenados por ID (ou por numero, se preferir)
            $numeros = NumeroSorteio::orderBy('id')->get();

            $esperado = 1;

            foreach ($numeros as $numero) {
                // Pula se já estiver correto
                if ($numero->codigo_continuo == $esperado) {
                    $esperado++;
                    continue;
                }

                // Verifica se já existe esse codigo_continuo
                while (NumeroSorteio::where('codigo_continuo', $esperado)->exists()) {
                    $esperado++;
                }

                $numero->codigo_continuo = $esperado;
                $numero->save();

                $this->line("Atualizado ID {$numero->id} com código {$esperado}");

                $esperado++;
            }
        });

        $this->info('Correção finalizada com sucesso.');
    }
}

<?php

namespace App\Filament\User\Resources\CupomResource\Pages;

use App\Filament\User\Resources\CupomResource;
use App\Services\NotaFiscalService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateCupom extends CreateRecord
{
    protected static string $resource = CupomResource::class;

    public $chave_acesso = '';

    public $loadData = false;

    public function getTitle(): string | Htmlable
    {
        return 'Cadastrar Cupom';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['data_cadastro'] = now();
        return $data;
    }

    public function getNfData()
    {
        $chave = $this->form->getState()['chave_acesso'] ?? null;

        // Aqui você faz a lógica de busca, por exemplo:
        $dados = NotaFiscalService::getDataFromQrCode($chave);

        // dd($dados);

        if ($dados) {
            $this->form->fill([
                'chave_acesso' => $chave,
                'valor_total' => $dados['total'],
                'fornecedor' => $dados['empresa_nome'],
                'data_emissao' => $dados['data_emissao'],
            ]);
        } else {
            // Opcional: notificação de erro
            $this->notify('danger', 'Nenhum dado encontrado com essa chave');
        }

        $this->loadData = true;
    }
}

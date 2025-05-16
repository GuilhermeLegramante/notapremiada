<?php

namespace App\Filament\Resources\CupomResource\Pages;

use App\Filament\Resources\CupomResource;
use App\Services\NotaFiscalService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CreateCupom extends CreateRecord
{
    protected static string $resource = CupomResource::class;

    public $chave_acesso = '';

    public $loadData = false;
    public $validado = false;

    public function getTitle(): string | Htmlable
    {
        return 'Cadastrar Cupom';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! $this->validado) {
            Notification::make()
                ->title('Erro ao salvar')
                ->body('A chave de acesso informada é inválida. Não é possível salvar o cupom.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'chave_acesso' => 'Chave de acesso inválida. Verifique e tente novamente.',
            ]);
        }

        $data['user_id'] = auth()->id();
        $data['data_cadastro'] = now();
        $data['validado'] = $this->validado;

        return $data;
    }

    public function getNfData()
    {
        $chave = $this->form->getState()['chave_acesso'] ?? null;

        $dados = NotaFiscalService::getDataFromQrCode($chave);

        if (! $dados) {
            $this->invalidateForm('Chave de acesso inválida', 'Não foi possível obter os dados da nota fiscal. Verifique a chave e tente novamente.');
            return;
        }

        if (! $this->cidadeValida($dados['cidade'])) {
            $this->invalidateForm('Cidade inválida', 'A cidade não faz parte deste sorteio.');
            return;
        }

        $anoAtual = now()->year;
        $anoNota = \Carbon\Carbon::parse($dados['data_emissao'])->year;

        if ($anoNota !== $anoAtual) {
            $this->invalidateForm('Ano inválido', 'A nota fiscal deve ter sido emitida no ano de ' . $anoAtual . '.');
            return;
        }

        $this->validado = true;
        $this->form->fill([
            'chave_acesso' => $chave,
            'valor_total' => $dados['total'],
            'fornecedor' => $dados['empresa_nome'],
            'data_emissao' => $dados['data_emissao'],
        ]);

        $this->loadData = true;
    }

    private function cidadeValida(string $cidade): bool
    {
        return mb_strtoupper($cidade) == mb_strtoupper(env('CIDADE_SORTEIO', 'CACEQUI'));
    }

    private function invalidateForm(string $titulo, string $mensagem): void
    {
        $this->validado = false;
        $this->form->fill([
            'chave_acesso' => $this->form->getState()['chave_acesso'] ?? null,
        ]);

        Notification::make()
            ->title($titulo)
            ->danger()
            ->body($mensagem)
            ->send();

        $this->loadData = false;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}

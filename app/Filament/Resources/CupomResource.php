<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupomResource\Pages\CreateCupom;
use App\Filament\Resources\CupomResource\Pages\ListCupoms;
use App\Models\Cupom;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\View;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Button;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Callback;
use Illuminate\Validation\ValidationException;

class CupomResource extends Resource
{
    protected static ?string $model = Cupom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'cupom';

    protected static ?string $pluralModelLabel = 'cupons';

    protected static ?string $slug = 'cupom';

    public static bool $shouldRegisterNavigation = true;

    public static bool $shouldRegisterPermissions = false; // Para desabilitar o shield

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados do Cupom')
                    ->description('Leia o QRCode para buscar os dados da nota fiscal ou informe os dados manualmente.')
                    ->schema([
                        Group::make([
                            View::make('components.qrcode-reader')
                                ->reactive()
                                ->visible(fn() => true),

                            View::make('components.manual-button'),

                            TextInput::make('chave_acesso')
                                ->label('Chave de Acesso')
                                ->unique()
                                ->maxLength(44)
                                ->minLength(44)
                                ->reactive()
                                ->visible(fn($livewire) => $livewire->isManual)
                                ->suffixAction(
                                    Action::make('buscarNota')
                                        ->icon('heroicon-o-magnifying-glass')
                                        ->tooltip('Buscar nota fiscal')
                                        ->action(function ($livewire, $set, $get) {
                                            $set('preview_chave', $get('chave_acesso'));

                                            if (
                                                (\App\Models\Cupom::where('chave_acesso', 'LIKE', "%{$get('chave_acesso')}%")
                                                    ->exists()) == true
                                            ) {
                                                $livewire->loadData = false;
                                                $livewire->mostrarPreview = false;

                                                Notification::make()
                                                    ->title('Chave de acesso inválida')
                                                    ->danger()
                                                    ->body('Chave de acesso já cadastrada')
                                                    ->send();
                                            } else {
                                                $livewire->mostrarPreview = true;
                                                $livewire->loadData = true;
                                            }
                                        })
                                ),

                            View::make('components.sefaz-preview')
                                ->visible(fn($livewire) => $livewire->isManual && $livewire->mostrarPreview)
                                ->reactive()
                                ->statePath('preview_chave'),

                            TextInput::make('valor_total')
                                ->label('Valor Total')
                                ->numeric()
                                ->readOnly(fn($livewire) => !$livewire->isManual)
                                ->prefix('R$')
                                ->required()
                                ->visible(fn($livewire) => $livewire->loadData),

                            TextInput::make('fornecedor')
                                ->readOnly(fn($livewire) => !$livewire->isManual)
                                ->maxLength(255)
                                ->visible(fn($livewire) => $livewire->loadData),

                            DatePicker::make('data_emissao')
                                ->label('Data de Emissão')
                                ->readOnly(fn($livewire) => !$livewire->isManual)
                                ->visible(fn($livewire) => $livewire->loadData),

                            Textarea::make('observacao')
                                ->maxLength(1000)
                                ->visible(fn($livewire) => $livewire->loadData),
                        ]),
                    ])
                    ->columns(1), // opcional: define colunas internas na section
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('numerosSorteio')
                    ->label('Números p/ Sorteio')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->numerosSorteio
                            ->pluck('id')
                            ->map(fn($id) => str_pad($id, 6, '0', STR_PAD_LEFT))
                            ->implode(', ')
                    ),
                TextColumn::make('data_cadastro')
                    ->sortable()
                    ->searchable()
                    ->label('Data do Cadastro')
                    ->date(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->label('Usuário'),
                TextColumn::make('fornecedor')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('valor_total')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BRL'))
                    ->searchable()
                    ->money('BRL'),
                IconColumn::make('validado')
                    ->sortable()
                    ->boolean()
                    ->label('Validado'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('verNota')
                    ->label('Ver Nota')
                    ->icon('heroicon-o-document-text')
                    ->url(function ($record) {
                        // Extrai os dois dígitos do modelo da nota (posições 21 e 22 da chave)
                        $modelo = substr($record->chave_acesso, 20, 2); // Índice base 0

                        // Se a nota for modelo 55 (NF-e)
                        if ($modelo === '55') {
                            return "https://www.nfe.fazenda.gov.br/portal/consultaRecaptcha.aspx?chNFe={$record->chave_acesso}&nVersao=100&tpAmb=1&x=1";
                        }

                        // Se a nota for modelo 65 (NFC-e)
                        if ($record->validado) {
                            return "https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p={$record->chave_acesso}";
                        }

                        return "https://www.sefaz.rs.gov.br/NFE/NFE-NFC.aspx?chaveNFe={$record->chave_acesso}";
                    })
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        if (auth()->user()->admin) {
            return parent::getEloquentQuery();
        } else {
            return parent::getEloquentQuery()->where('user_id', auth()->id());
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCupoms::route('/'),
            'create' => CreateCupom::route('/create'),
        ];
    }
}

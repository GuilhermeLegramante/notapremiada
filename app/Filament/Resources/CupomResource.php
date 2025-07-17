<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupomResource\Pages\CreateCupom;
use App\Filament\Resources\CupomResource\Pages\ListCupoms;
use App\Models\Cupom;
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
                                ->reactive()
                                ->visible(fn($livewire) => $livewire->isManual)
                                ->afterStateUpdated(fn($state, callable $set) => $set('preview_chave', $state)),

                            View::make('components.sefaz-preview')
                                ->visible(fn($livewire) => $livewire->isManual)
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
                    ->label('Data do Cadastro')
                    ->date(),
                TextColumn::make('user.name')
                    ->label('Usuário'),
                TextColumn::make('fornecedor'),
                // TextColumn::make('chave_acesso'),
                TextColumn::make('valor_total')->money('BRL'),
                IconColumn::make('validado')
                    ->boolean()
                    ->label('Validado'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('verNota')
                    ->label('Ver Nota')
                    ->icon('heroicon-o-document-text')
                    ->url(
                        fn($record) =>
                        $record->validado
                            ? "https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p={$record->chave_acesso}"
                            : "https://www.sefaz.rs.gov.br/NFE/NFE-NFC.aspx?chaveNFe={$record->chave_acesso}"
                    )
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

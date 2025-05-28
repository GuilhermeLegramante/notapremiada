<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupomResource\Pages\CreateCupom;
use App\Filament\Resources\CupomResource\Pages\ListCupoms;
use App\Filament\User\Resources\CupomResource\Pages;
use App\Filament\User\Resources\CupomResource\RelationManagers;
use App\Models\Cupom;
use App\Services\NotaFiscalService;
use DesignTheBox\BarcodeField\Forms\Components\BarcodeInput;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\View;
use Filament\Tables\Columns\IconColumn;

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
                    ->description('Informe a chave de acesso e busque os dados da nota fiscal.')
                    ->schema([
                        Group::make([
                            View::make('components.qrcode-reader')
                                ->visible(fn() => true),

                            TextInput::make('chave_acesso')
                                ->label('Chave de Acesso')
                                ->required()
                                ->unique()
                                ->reactive()
                                ->maxLength(255),

                            \Filament\Forms\Components\Actions::make([
                                Action::make('getData')
                                    ->label('Buscar Dados')
                                    ->action('getNfData')
                                    ->requiresConfirmation(false)
                                    ->visible(fn($get) => filled($get('chave_acesso')))
                                    ->color('primary'),
                            ]),

                            TextInput::make('valor_total')
                                ->label('Valor Total')
                                ->numeric()
                                ->readOnly()
                                ->prefix('R$')
                                ->required()
                                ->visible(fn($livewire) => $livewire->loadData),

                            TextInput::make('fornecedor')
                                ->readOnly()
                                ->maxLength(255)
                                ->visible(fn($livewire) => $livewire->loadData),

                            DatePicker::make('data_emissao')
                                ->label('Data de Emissão')
                                ->readOnly()
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
            ->paginationPageOptions([10, 25, 50, 100])
            ->columns([
                TextColumn::make('numerosSorteio')
                    ->label('Números p/ Sorteio')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->numerosSorteio->pluck('id')->implode(', ')
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
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => "https://dfe-portal.svrs.rs.gov.br/Dfe/QrCodeNFce?p={$record->chave_acesso}|2|1|1|")
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

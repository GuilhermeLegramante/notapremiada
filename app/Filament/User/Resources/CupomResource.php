<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CupomResource\Pages;
use App\Filament\User\Resources\CupomResource\RelationManagers;
use App\Models\Cupom;
use App\Services\NotaFiscalService;
use DesignTheBox\BarcodeField\Forms\Components\BarcodeInput;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
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


class CupomResource extends Resource
{
    protected static ?string $model = Cupom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'cupom';

    protected static ?string $pluralModelLabel = 'cupons';

    protected static ?string $slug = 'cupom';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    View::make('components.qrcode-reader')
                        ->visible(fn() => true),

                    TextInput::make('chave_acesso')
                        ->label('Chave de Acesso')
                        ->required()
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


                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Usuário'),
                TextColumn::make('chave_acesso'),
                TextColumn::make('valor_total')->money('BRL'),
                TextColumn::make('fornecedor'),
                TextColumn::make('data_cadastro')->date(),
                TextColumn::make('numerosSorteio')
                    ->label('Números')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->numerosSorteio->pluck('numero')->implode(', ')
                    ),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
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
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCupoms::route('/'),
            'create' => Pages\CreateCupom::route('/create'),
            'edit' => Pages\EditCupom::route('/{record}/edit'),
        ];
    }

    
}

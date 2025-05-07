<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CupomResource\Pages;
use App\Filament\Resources\CupomResource\RelationManagers;
use App\Models\Cupom;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                TextInput::make('chave_acesso')
                    ->label('Chave de Acesso')
                    ->required()
                    ->maxLength(255),

                TextInput::make('valor_total')
                    ->label('Valor Total')
                    ->numeric()
                    ->prefix('R$')
                    ->required(),

                TextInput::make('fornecedor')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('data_cadastro')
                    ->label('Data de Cadastro')
                    ->required(),

                Textarea::make('observacao')
                    ->maxLength(1000),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCupoms::route('/'),
            'create' => Pages\CreateCupom::route('/create'),
            'edit' => Pages\EditCupom::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        dd($data);
        $data['user_id'] = auth()->id();
        return $data;
    }
}

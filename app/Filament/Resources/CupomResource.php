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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Callback;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

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
                                ->required(fn($livewire) => $livewire->loadData)
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
                                ->required(fn($livewire) => $livewire->loadData)
                                ->visible(fn($livewire) => $livewire->loadData),

                            TextInput::make('fornecedor')
                                ->readOnly(fn($livewire) => !$livewire->isManual)
                                ->required(fn($livewire) => $livewire->loadData)
                                ->maxLength(255)
                                ->visible(fn($livewire) => $livewire->loadData),

                            DatePicker::make('data_emissao')
                                ->label('Data de Emissão')
                                ->required(fn($livewire) => $livewire->loadData)
                                ->readOnly(fn($livewire) => !$livewire->isManual)
                                ->visible(fn($livewire) => $livewire->loadData),

                            FileUpload::make('arquivo')
                                ->label('Foto ou arquivo da nota fiscal')
                                ->required(fn($livewire) => $livewire->loadData)
                                ->directory('cupons')
                                ->imagePreviewHeight('200')
                                ->downloadable()
                                ->openable()
                                ->preserveFilenames()
                                ->maxSize(10240) // opcional: 10MB
                                ->disabled(fn($livewire) => !$livewire->isManual)
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
            ->persistFiltersInSession()
            ->persistSortInSession()
            ->defaultSort('id', 'desc')
            ->columns([
                // TextColumn::make('numerosSorteio')
                //     ->label('Números p/ Sorteio')
                //     ->formatStateUsing(
                //         fn($record) =>
                //         $record->numerosSorteio
                //             ->pluck('id')
                //             ->map(fn($id) => str_pad($id, 6, '0', STR_PAD_LEFT))
                //             ->implode(', ')
                //     ),
                TextColumn::make('numerosSorteio')
                    ->label('Números p/ Sorteio')
                    ->formatStateUsing(fn($state, $record) => 'Ver números')
                    ->tooltip(fn($record) => Str::limit(
                        $record->numerosSorteio
                            ->pluck('codigo_continuo')
                            ->map(fn($codigo_continuo) => str_pad($codigo_continuo, 6, '0', STR_PAD_LEFT))
                            ->implode(', '),
                        200
                    ))
                    ->extraAttributes([
                        'class' => 'cursor-pointer text-primary underline',
                    ]),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->label('Usuário'),
                TextColumn::make('fornecedor')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('valor_total')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total')->money('BRL'))
                    ->searchable()
                    ->toggleable()
                    ->money('BRL'),
                ImageColumn::make('arquivo')
                    ->disk('public')
                    ->height(50)
                    ->circular()
                    ->toggleable()
                    ->url(fn($record) => Storage::disk('public')->url($record->arquivo))
                    ->openUrlInNewTab(),
                IconColumn::make('validado')
                    ->sortable()
                    ->boolean()
                    ->toggleable()
                    ->label('Validado'),

                TextColumn::make('updated_at')
                    ->label('Editado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('data_cadastro')
                    ->form([
                        Forms\Components\DatePicker::make('data_inicio')->label('Data Início'),
                        Forms\Components\DatePicker::make('data_fim')->label('Data Fim'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['data_inicio'], fn($q) => $q->whereDate('data_cadastro', '>=', $data['data_inicio']))
                            ->when($data['data_fim'], fn($q) => $q->whereDate('data_cadastro', '<=', $data['data_fim']));
                    }),

                SelectFilter::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->visible(fn() => auth()->user()->admin)
                    ->searchable(),

                Filter::make('fornecedor')
                    ->form([
                        Forms\Components\TextInput::make('fornecedor')
                            ->label('Fornecedor')
                            ->placeholder('Nome do fornecedor'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['fornecedor'], fn($q) => $q->where('fornecedor', 'like', "%{$data['fornecedor']}%"));
                    }),

                Filter::make('valor_total')
                    ->form([
                        Forms\Components\TextInput::make('valor_min')
                            ->label('Valor Mínimo')
                            ->numeric(),
                        Forms\Components\TextInput::make('valor_max')
                            ->label('Valor Máximo')
                            ->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['valor_min'], fn($q) => $q->where('valor_total', '>=', $data['valor_min']))
                            ->when($data['valor_max'], fn($q) => $q->where('valor_total', '<=', $data['valor_max']));
                    }),

                SelectFilter::make('validado')
                    ->label('Validado')
                    ->options([
                        1 => 'Sim',
                        0 => 'Não',
                    ]),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Tables\Actions\Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->actions([
                // ActionGroup::make([
                Tables\Actions\Action::make('verNota')
                    ->label('')
                    ->tooltip('Ver Nota (SEFAZ)')
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

                Tables\Actions\Action::make('verDetalhes')
                    ->label('')
                    ->tooltip('Detalhes')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detalhes do Cupom')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalContent(function ($record) {
                        return view('detalhes-cupom', compact('record'));
                    }),

                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->tooltip('Excluir'),
                // ]),
            ], position: ActionsPosition::BeforeColumns)
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

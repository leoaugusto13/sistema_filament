<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnosResource\Pages;
use App\Filament\Resources\AnosResource\RelationManagers;
use App\Models\Anos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\ActionGroup;

class AnosResource extends Resource
{
    protected static ?string $model = Anos::class;

    // Personalização da navegação
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Anos Letivos';
    protected static ?string $modelLabel = 'Ano Letivo';
    protected static ?string $pluralModelLabel = 'Anos Letivos';
    protected static ?string $navigationGroup = 'Configurações de Sistema';
    protected static ?int $navigationSort = 1;

    // Personalização do resource
    protected static ?string $recordTitleAttribute = 'ano';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informações do Ano Letivo')
                    ->description('Configure as informações básicas do ano letivo')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('ano')
                                    ->label('Ano')
                                    ->required()
                                    ->numeric()
                                    ->minValue(2020)
                                    ->maxValue(2030)
                                    ->placeholder('Ex: 2024')
                                    ->helperText('Digite o ano letivo (formato: YYYY)')
                                    ->columnSpan(1),

                                Toggle::make('ativo')
                                    ->label('Ativo')
                                    ->default(true)
                                    ->helperText('Define se este ano letivo está ativo')
                                    ->columnSpan(1),
                            ]),

                        Textarea::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Descrição opcional do ano letivo')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Datas do Período Letivo')
                    ->description('Configure as datas de início e fim do ano letivo')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('data_inicio')
                                    ->label('Data de Início')
                                    ->required()
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->closeOnDateSelection()
                                    ->columnSpan(1),

                                DatePicker::make('data_fim')
                                    ->label('Data de Fim')
                                    ->required()
                                    ->displayFormat('d/m/Y')
                                    ->native(false)
                                    ->closeOnDateSelection()
                                    ->after('data_inicio')
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ano')
                    ->label('Ano')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->size('lg'),

                TextColumn::make('data_inicio')
                    ->label('Início')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('data_fim')
                    ->label('Fim')
                    ->date('d/m/Y')
                    ->sortable(),

                BooleanColumn::make('ativo')
                    ->label('Status')
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('descricao')
                    ->label('Descrição')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('ativo')
                    ->label('Status')
                    ->placeholder('Todos os anos')
                    ->trueLabel('Apenas ativos')
                    ->falseLabel('Apenas inativos'),

                Filter::make('ano_range')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('ano_de')
                                    ->label('Ano de')
                                    ->numeric(),
                                TextInput::make('ano_ate')
                                    ->label('Ano até')
                                    ->numeric(),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['ano_de'],
                                fn (Builder $query, $date): Builder => $query->where('ano', '>=', $data['ano_de']),
                            )
                            ->when(
                                $data['ano_ate'],
                                fn (Builder $query, $date): Builder => $query->where('ano', '<=', $data['ano_ate']),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['ano_de'] ?? null) {
                            $indicators[] = 'Ano de: ' . $data['ano_de'];
                        }
                        if ($data['ano_ate'] ?? null) {
                            $indicators[] = 'Ano até: ' . $data['ano_ate'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(),
                ])
                ->label('Ações')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('primary')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('ativar')
                        ->label('Ativar selecionados')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['ativo' => true]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Ativar anos letivos')
                        ->modalDescription('Tem certeza de que deseja ativar os anos letivos selecionados?'),
                    Tables\Actions\BulkAction::make('desativar')
                        ->label('Desativar selecionados')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['ativo' => false]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Desativar anos letivos')
                        ->modalDescription('Tem certeza de que deseja desativar os anos letivos selecionados?'),
                ]),
            ])
            ->defaultSort('ano', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            // Adicione relation managers aqui se necessário
            // Ex: TurmasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnos::route('/'),
            'create' => Pages\CreateAnos::route('/create'),
            'view' => Pages\ViewAnos::route('/{record}'),
            'edit' => Pages\EditAnos::route('/{record}/edit'),
        ];
    }

    // Método para customizar a query global
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Método para definir badges de navegação
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('ativo', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConferenciasResource\Pages;
use App\Filament\Resources\ConferenciasResource\RelationManagers;
use App\Models\Conferencias;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ConferenciasResource extends Resource
{
    protected static ?string $model = Conferencias::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Conferências';
    protected static ?string $modelLabel = 'Conferencia';
    protected static ?string $pluralModelLabel = 'Conferências';
    protected static ?string $navigationGroup = 'Configurações de Sistema';
    protected static ?int $navigationSort = 1;

      // Personalização do resource
    protected static ?string $recordTitleAttribute = 'conferencia';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações da Conferência')
                    ->description('Dados básicos da conferência')
                    ->schema([
                        Forms\Components\Select::make('id_ano')
                            ->label('Ano')
                            ->relationship('ano', 'ano')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('ano')
                                    ->label('Ano')
                                    ->numeric()
                                    ->minValue(2020)
                                    ->maxValue(2030)
                                    ->required(),
                                Forms\Components\Textarea::make('observacoes')
                                    ->label('Observações')
                                    ->rows(3),
                            ])
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('nome')
                            ->label('Nome da Conferência')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Detalhes do Evento')
                    ->description('Informações detalhadas sobre a conferência')
                    ->schema([
                        Forms\Components\Textarea::make('descr_local')
                            ->label('Descrição do Local')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('objetivo')
                            ->label('Objetivo')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                                'link',
                                'redo',
                                'undo',
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
                       ->columns([
                Tables\Columns\TextColumn::make('id_conferencia')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nome')
                    ->label('Nome da Conferência')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('ano.ano')
                    ->label('Ano')
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('descr_local')
                    ->label('Local')
                    ->searchable()
                    ->limit(50)
                    ->wrap()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('objetivo')
                    ->label('Objetivo')
                    ->html()
                    ->limit(80)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_ano')
                    ->label('Filtrar por Ano')
                    ->relationship('ano', 'ano')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                Tables\Filters\Filter::make('nome')
                    ->form([
                        Forms\Components\TextInput::make('nome')
                            ->label('Nome da Conferência')
                            ->placeholder('Digite o nome para filtrar'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['nome'],
                            fn ($query, $nome) => $query->where('nome', 'like', "%{$nome}%")
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('id_conferencia', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

  
     public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informações Gerais')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('id_conferencia')
                                    ->label('ID da Conferência')
                                    ->badge()
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('ano.ano')
                                    ->label('Ano')
                                    ->badge()
                                    ->color('success'),

                                Infolists\Components\TextEntry::make('nome')
                                    ->label('Nome da Conferência')
                                    ->weight('bold')
                                    ->size('lg'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Detalhes do Evento')
                    ->schema([
                        Infolists\Components\TextEntry::make('descr_local')
                            ->label('Descrição do Local')
                            ->prose()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('objetivo')
                            ->label('Objetivo')
                            ->html()
                            ->prose()
                            ->columnSpanFull(),
                    ]),
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
            'index' => Pages\ListConferencias::route('/'),
            'create' => Pages\CreateConferencias::route('/create'),
            'view' => Pages\ViewConferencias::route('/{record}'),
            'edit' => Pages\EditConferencias::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['ano']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nome', 'descr_local', 'ano.ano'];
    }

}

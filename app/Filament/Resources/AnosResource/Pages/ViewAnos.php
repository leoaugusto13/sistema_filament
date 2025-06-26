<?php


namespace App\Filament\Resources\AnosResource\Pages;

use App\Filament\Resources\AnosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ViewAnos extends ViewRecord
{
    protected static string $resource = AnosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar')
                ->icon('heroicon-o-pencil'),
            Actions\DeleteAction::make()
                ->label('Excluir')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informações do Ano Letivo')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('ano')
                                    ->label('Ano')
                                    ->weight(FontWeight::Bold)
                                    ->size('lg'),

                                Infolists\Components\IconEntry::make('ativo')
                                    ->label('Status')
                                    ->boolean()
                                    ->trueIcon('heroicon-o-check-badge')
                                    ->falseIcon('heroicon-o-x-mark')
                                    ->trueColor('success')
                                    ->falseColor('danger'),
                            ]),

                        Infolists\Components\TextEntry::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Nenhuma descrição fornecida')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Período Letivo')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('data_inicio')
                                    ->label('Data de Início')
                                    ->date('d/m/Y'),

                                Infolists\Components\TextEntry::make('data_fim')
                                    ->label('Data de Fim')
                                    ->date('d/m/Y'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Informações do Sistema')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Criado em')
                                    ->dateTime('d/m/Y H:i:s'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Atualizado em')
                                    ->dateTime('d/m/Y H:i:s'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
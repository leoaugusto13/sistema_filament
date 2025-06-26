<?php

namespace App\Filament\Resources\ConferenciasResource\Pages;

use App\Filament\Resources\ConferenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListConferencias extends ListRecords
{
    protected static string $resource = ConferenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nova Conferência')
                ->icon('heroicon-o-plus')
                ->color('success'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todas' => Tab::make('Todas as Conferências')
                ->icon('heroicon-o-queue-list')
                ->badge(fn() => $this->getModel()::count()),

            'recentes' => Tab::make('Últimos 2 Anos')
                ->icon('heroicon-o-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('ano', function ($query) {
                    $query->where('ano', '>=', now()->year - 1);
                }))
                ->badge(fn() => $this->getModel()::whereHas('ano', function ($query) {
                    $query->where('ano', '>=', now()->year - 1);
                })->count()),

            '2024' => Tab::make('2024')
                ->icon('heroicon-o-calendar')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('ano', function ($query) {
                    $query->where('ano', 2024);
                }))
                ->badge(fn() => $this->getModel()::whereHas('ano', function ($query) {
                    $query->where('ano', 2024);
                })->count()),

            '2023' => Tab::make('2023')
                ->icon('heroicon-o-calendar')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('ano', function ($query) {
                    $query->where('ano', 2023);
                }))
                ->badge(fn() => $this->getModel()::whereHas('ano', function ($query) {
                    $query->where('ano', 2023);
                })->count()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Adicione widgets aqui se necessário
        ];
    }

    public function getTitle(): string
    {
        return 'Conferências';
    }

    public function getHeading(): string
    {
        return 'Gerenciar Conferências';
    }

    public function getSubheading(): ?string
    {
        return 'Visualize e gerencie todas as conferências cadastradas no sistema';
    }
}

<?php

// 1. WIDGET DE ESTATÍSTICAS
namespace App\Filament\Resources\ConferenciasResource\Widgets;

use App\Models\Conferencias;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConferenciasStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Conferencias::count())
                ->description('Conferências cadastradas')
                ->descriptionIcon('heroicon-m-presentation-chart-bar')
                ->color('primary'),
                
            Stat::make('Este Ano', Conferencias::whereHas('ano', function($q) {
                    $q->where('ano', now()->year);
                })->count())
                ->description('Conferências de ' . now()->year)
                ->descriptionIcon('heroicon-m-calendar')
                ->color('success'),
                
            Stat::make('Pendentes', 5) // Exemplo
                ->description('Aguardando aprovação')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
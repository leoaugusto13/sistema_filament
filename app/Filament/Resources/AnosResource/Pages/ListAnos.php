<?php

// App/Filament/Resources/AnosResource/Pages/ListAnos.php

namespace App\Filament\Resources\AnosResource\Pages;

use App\Filament\Resources\AnosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAnos extends ListRecords
{
    protected static string $resource = AnosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Ano Letivo')
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'todos' => Tab::make('Todos')
                ->badge(fn () => $this->getResource()::getEloquentQuery()->count()),
            
            'ativos' => Tab::make('Ativos')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('ativo', true))
                ->badge(fn () => $this->getResource()::getEloquentQuery()->where('ativo', true)->count()),
            
            'inativos' => Tab::make('Inativos')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('ativo', false))
                ->badge(fn () => $this->getResource()::getEloquentQuery()->where('ativo', false)->count()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Adicione widgets aqui se necessário
        ];
    }
}
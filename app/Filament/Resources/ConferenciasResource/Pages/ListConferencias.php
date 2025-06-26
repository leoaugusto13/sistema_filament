<?php

namespace App\Filament\Resources\ConferenciasResource\Pages;

use App\Filament\Resources\ConferenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConferencias extends ListRecords
{
    protected static string $resource = ConferenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\AnosResource\Pages;

use App\Filament\Resources\AnosResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAnos extends CreateRecord
{
    protected static string $resource = AnosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ano letivo criado!')
            ->body('O ano letivo foi criado com sucesso.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Adicione lógica personalizada antes de criar se necessário
        return $data;
    }

    protected function afterCreate(): void
    {
        // Lógica após criação se necessário
    }
}
<?php

namespace App\Filament\Resources\AnosResource\Pages;

use App\Filament\Resources\AnosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAnos extends EditRecord
{
    protected static string $resource = AnosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Visualizar')
                ->icon('heroicon-o-eye'),
            Actions\DeleteAction::make()
                ->label('Excluir')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ano letivo atualizado!')
            ->body('As alterações foram salvas com sucesso.');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Adicione lógica personalizada antes de salvar se necessário
        return $data;
    }

    protected function afterSave(): void
    {
        // Lógica após salvar se necessário
    }
}

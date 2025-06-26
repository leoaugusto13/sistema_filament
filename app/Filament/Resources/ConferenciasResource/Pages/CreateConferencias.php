<?php

namespace App\Filament\Resources\ConferenciasResource\Pages;

use App\Filament\Resources\ConferenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateConferencias extends CreateRecord
{
    protected static string $resource = ConferenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('voltar')
                ->label('Voltar à Lista')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Conferência criada!')
            ->body('A conferência foi criada com sucesso.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function getTitle(): string
    {
        return 'Nova Conferência';
    }

    public function getHeading(): string
    {
        return 'Criar Nova Conferência';
    }

    public function getSubheading(): ?string
    {
        return 'Preencha os dados abaixo para criar uma nova conferência';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Aqui você pode modificar os dados antes de salvar
        // Por exemplo, limpar campos desnecessários ou formatar dados
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Ações após criar o registro
        // Por exemplo, enviar notificações, logs, etc.
    }
}

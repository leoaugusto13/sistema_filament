<?php

namespace App\Filament\Resources\ConferenciasResource\Pages;

use App\Filament\Resources\ConferenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditConferencias extends EditRecord
{
    protected static string $resource = ConferenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('voltar')
                ->label('Voltar')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getResource()::getUrl('view', ['record' => $this->getRecord()])),

            Actions\ViewAction::make()
                ->label('Visualizar')
                ->icon('heroicon-o-eye')
                ->color('info'),

            Actions\DeleteAction::make()
                ->label('Excluir')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Excluir Conferência')
                ->modalDescription('Tem certeza que deseja excluir esta conferência? Esta ação não pode ser desfeita.')
                ->modalSubmitActionLabel('Sim, excluir')
                ->modalCancelActionLabel('Cancelar')
                ->successRedirectUrl($this->getResource()::getUrl('index')),

            Actions\Action::make('resetar')
                ->label('Resetar Alterações')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Resetar Alterações')
                ->modalDescription('Isso irá desfazer todas as alterações não salvas.')
                ->modalSubmitActionLabel('Resetar')
                ->action(function () {
                    $this->fillForm();
                    
                    Notification::make()
                        ->title('Alterações resetadas')
                        ->body('O formulário foi resetado para os valores originais.')
                        ->warning()
                        ->send();
                }),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Conferência atualizada!')
            ->body('As alterações foram salvas com sucesso.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    public function getTitle(): string
    {
        return 'Editar Conferência';
    }

    public function getHeading(): string
    {
        return 'Editar: ' . $this->getRecord()->nome;
    }

    public function getSubheading(): ?string
    {
        return 'Ano: ' . $this->getRecord()->ano?->ano . ' • ID: ' . $this->getRecord()->id_conferencia;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Aqui você pode modificar os dados antes de salvar
        // Por exemplo, formatar campos ou adicionar metadados
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Ações após salvar o registro
        // Por exemplo, limpar cache, enviar notificações, etc.
    }

    protected function beforeFill(): void
    {
        // Ações antes de preencher o formulário
        // Por exemplo, verificar permissões específicas
    }
}

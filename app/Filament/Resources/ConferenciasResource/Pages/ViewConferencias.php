<?php

namespace App\Filament\Resources\ConferenciasResource\Pages;

use App\Filament\Resources\ConferenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewConferencias extends ViewRecord
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

            Actions\EditAction::make()
                ->label('Editar Conferência')
                ->icon('heroicon-o-pencil')
                ->color('warning'),

            Actions\DeleteAction::make()
                ->label('Excluir Conferência')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Excluir Conferência')
                ->modalDescription('Tem certeza que deseja excluir esta conferência? Esta ação não pode ser desfeita.')
                ->modalSubmitActionLabel('Sim, excluir')
                ->modalCancelActionLabel('Cancelar'),

            Actions\Action::make('duplicar')
                ->label('Duplicar Conferência')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Duplicar Conferência')
                ->modalDescription('Isso criará uma nova conferência com os mesmos dados desta.')
                ->modalSubmitActionLabel('Duplicar')
                ->action(function () {
                    $originalRecord = $this->getRecord();
                    
                    $newRecord = $originalRecord->replicate();
                    $newRecord->nome = $originalRecord->nome . ' (Cópia)';
                    $newRecord->save();
                    
                    $this->redirect($this->getResource()::getUrl('edit', ['record' => $newRecord]));
                }),
        ];
    }

    public function getTitle(): string
    {
        return 'Visualizar Conferência';
    }

    public function getHeading(): string
    {
        return $this->getRecord()->nome;
    }

    public function getSubheading(): ?string
    {
        return 'Ano: ' . $this->getRecord()->ano?->ano . ' • ID: ' . $this->getRecord()->id_conferencia;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Adicione widgets personalizados aqui se necessário
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // Adicione widgets de rodapé aqui se necessário
        ];
    }
}
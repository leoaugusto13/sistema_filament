<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anos', function (Blueprint $table) {
            $table->id();
            
            // Campo principal do ano letivo
            $table->year('ano')
                ->unique()
                ->comment('Ano letivo (ex: 2024)');
            
            // Descrição opcional
            $table->text('descricao')
                ->nullable()
                ->comment('Descrição opcional do ano letivo');
            
            // Status do ano letivo
            $table->boolean('ativo')
                ->default(true)
                ->comment('Indica se o ano letivo está ativo');
            
            // Período letivo
            $table->date('data_inicio')
                ->comment('Data de início do ano letivo');
            
            $table->date('data_fim')
                ->comment('Data de fim do ano letivo');
            
            // Timestamps
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index('ativo');
            $table->index('ano');
            $table->index(['data_inicio', 'data_fim']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anos');
    }
};
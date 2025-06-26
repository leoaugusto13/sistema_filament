<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Anos extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ano',
        'descricao',
        'ativo',
        'data_inicio',
        'data_fim',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ano' => 'integer',
        'ativo' => 'boolean',
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ano' => 'integer',
            'ativo' => 'boolean',
            'data_inicio' => 'date',
            'data_fim' => 'date',
        ];
    }

    /**
     * Scope para buscar apenas anos ativos
     */
    public function scopeAtivo(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para buscar apenas anos inativos
     */
    public function scopeInativo(Builder $query): Builder
    {
        return $query->where('ativo', false);
    }

    /**
     * Scope para buscar por ano específico
     */
    public function scopeByAno(Builder $query, int $ano): Builder
    {
        return $query->where('ano', $ano);
    }

    /**
     * Scope para buscar anos em um período
     */
    public function scopeEntrePeriodo(Builder $query, Carbon $dataInicio, Carbon $dataFim): Builder
    {
        return $query->where(function ($q) use ($dataInicio, $dataFim) {
            $q->whereBetween('data_inicio', [$dataInicio, $dataFim])
              ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
              ->orWhere(function ($q2) use ($dataInicio, $dataFim) {
                  $q2->where('data_inicio', '<=', $dataInicio)
                     ->where('data_fim', '>=', $dataFim);
              });
        });
    }

    /**
     * Scope para buscar o ano letivo atual
     */
    public function scopeAtual(Builder $query): Builder
    {
        $hoje = Carbon::now();
        return $query->where('data_inicio', '<=', $hoje)
                    ->where('data_fim', '>=', $hoje)
                    ->where('ativo', true);
    }

    /**
     * Accessor para formatar o período completo
     */
    public function getPeriodoCompletoAttribute(): string
    {
        return $this->data_inicio->format('d/m/Y') . ' - ' . $this->data_fim->format('d/m/Y');
    }

    /**
     * Accessor para verificar se é o ano atual
     */
    public function getEhAnoAtualAttribute(): bool
    {
        $hoje = Carbon::now();
        return $this->ativo && 
               $this->data_inicio <= $hoje && 
               $this->data_fim >= $hoje;
    }

    /**
     * Accessor para calcular duração em dias
     */
    public function getDuracaoEmDiasAttribute(): int
    {
        return $this->data_inicio->diffInDays($this->data_fim) + 1;
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute(): string
    {
        return $this->ativo ? 'Ativo' : 'Inativo';
    }

    /**
     * Boot method para eventos do model
     */
    protected static function boot()
    {
        parent::boot();

        // Evento antes de criar
        static::creating(function ($anos) {
            // Validação personalizada: data_fim deve ser maior que data_inicio
            if ($anos->data_fim <= $anos->data_inicio) {
                throw new \InvalidArgumentException('A data de fim deve ser posterior à data de início.');
            }
        });

        // Evento antes de atualizar
        static::updating(function ($anos) {
            // Validação personalizada: data_fim deve ser maior que data_inicio
            if ($anos->data_fim <= $anos->data_inicio) {
                throw new \InvalidArgumentException('A data de fim deve ser posterior à data de início.');
            }
        });
    }

    /**
     * Relacionamentos (exemplos que podem ser úteis)
     */
    
    // Exemplo: Se você tiver uma tabela de turmas
    // public function turmas()
    // {
    //     return $this->hasMany(Turma::class, 'ano_id');
    // }

    // Exemplo: Se você tiver uma tabela de matrículas
    // public function matriculas()
    // {
    //     return $this->hasMany(Matricula::class, 'ano_id');
    // }

    // Exemplo: Se você tiver uma tabela de períodos/semestres
    // public function periodos()
    // {
    //     return $this->hasMany(Periodo::class, 'ano_id');
    // }

    /**
     * Métodos utilitários estáticos
     */

    /**
     * Buscar o ano letivo ativo atual
     */
    public static function anoAtual(): ?self
    {
        return static::atual()->first();
    }

    /**
     * Verificar se existe ano letivo ativo
     */
    public static function temAnoAtivo(): bool
    {
        return static::ativo()->exists();
    }

    /**
     * Buscar anos disponíveis para seleção
     */
    public static function anosDisponiveis(): array
    {
        return static::ativo()
                    ->orderBy('ano', 'desc')
                    ->pluck('ano', 'id')
                    ->toArray();
    }

    /**
     * String representation of the model
     */
    public function __toString(): string
    {
        return "Ano Letivo {$this->ano}" . ($this->ativo ? ' (Ativo)' : ' (Inativo)');
    }
}
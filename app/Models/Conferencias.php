<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar para o relacionamento

class Conferencias extends Model
{
    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'conferencias';

    /**
     * A chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey = 'id_conferencia';

    /**
     * Indica se o ID é auto-incrementável.
     * A coluna `idconferencia` é AUTO_INCREMENT, então deixamos como true (padrão).
     *
     * @var bool
     */
    public $incrementing = true; // Padrão do Laravel, mas bom explicitar

    /**
     * O tipo da chave primária.
     * `idconferencia` é INT, então 'int' é o tipo correto.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indica se o model deve ser timestamped.
     * Como sua tabela 'conferencia' não tem as colunas 'created_at' e 'updated_at',
     * definimos esta propriedade como false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Os atributos que são preenchíveis em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_ano',
        'nome',
        'descr_local',
        'objetivo',
    ];

    /**
     * Define o relacionamento BelongsTo com o model Ano.
     * Uma Conferência pertence a um Ano.
     */
    public function ano(): BelongsTo
    {
        return $this->belongsTo(Anos::class, 'id_ano', 'id_ano');
        // O primeiro 'idano' é a chave estrangeira na tabela 'conferencia'.
        // O segundo 'idano' é a chave primária na tabela 'ano'.
    }
}

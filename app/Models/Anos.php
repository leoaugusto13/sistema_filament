<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anos extends Model
{
    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'anos';

    /**
     * A chave primária da tabela.
     *
     * @var string
     */
    protected $primaryKey = 'id_ano';

    /**
     * Indica se o ID é auto-incrementável.
     * Por padrão, Laravel espera um ID auto-incrementável. Como 'idano' é a própria chave e não se auto-incrementa,
     * devemos definir esta propriedade como false.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * O tipo da chave primária.
     * Laravel espera 'int' ou 'bigint' por padrão. Como 'idano' é um SMALLINT, é bom especificar.
     *
     * @var string
     */
    protected $keyType = 'integer'; // Ou 'smallint', mas 'integer' geralmente funciona bem para SMALLINT.

    /**
     * Indica se o model deve ser timestamped.
     * Por padrão, Laravel espera colunas 'created_at' e 'updated_at'. Como não as temos, definimos como false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Os atributos que são preenchíveis em massa.
     * Se você pretende criar registros como `Ano::create(['idano' => 2025])`, adicione 'idano' aqui.
     * Caso contrário, remova ou deixe vazio se só for buscar/atualizar.
     *
     * @var array
     */
    protected $fillable = [
        'id_ano',
    ];

    // Se você tiver outras colunas no futuro, adicione-as ao $fillable se forem preenchíveis em massa,
    // ou ao $guarded se não quiser que sejam preenchíveis em massa.

    /**
     * Exemplo de método de relacionamento (se aplicável).
     * Por exemplo, se um ano tem muitos orçamentos.
     */
    /*
    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class, 'ano_idano_fk'); // Ajuste 'ano_idano_fk' para a sua chave estrangeira real.
    }
    */
}
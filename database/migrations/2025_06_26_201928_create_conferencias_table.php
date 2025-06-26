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
        Schema::create('conferencias', function (Blueprint $table) {
            $table->increments('id_conferencia'); // int(11) AUTO_INCREMENT, PRIMARY KEY
            $table->unsignedBigInteger('id_ano')->comment('Chave estrangeira para o ano de exercicio da conferencia');

    // Exemplo 2: Se 'anos.id' for SMALLINT (como sugerimos para 'ano.idano')
    // $table->smallInteger('id_ano')->comment('Chave estrangeira para o ano de exercicio da conferencia');

    // Exemplo 3: Se 'anos.id' for INT(11) (padrão antigo ou PK não unsigned)
    // $table->integer('id_ano')->comment('Chave estrangeira para o ano de exercicio da conferencia');

    $table->string('nome', 90);
    $table->string('descr_local', 250);
    $table->string('objetivo', 500);

    // ... e o resto da sua migration para as outras colunas

    // Definição da chave estrangeira
    $table->foreign('id_ano')
          ->references('id') // <--- Confirme se é 'id' ou 'idano' em 'anos'
          ->on('anos')       // <--- Confirme se é 'anos' ou 'ano'
          ->onDelete('restrict')
          ->onUpdate('restrict');

        });

        // O charset padrão é configurado no config/database.php para Laravel 8+
        // Se precisar especificar explicitamente para a tabela:
        // DB::statement("ALTER TABLE `conferencia` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conferencias');
    }
};

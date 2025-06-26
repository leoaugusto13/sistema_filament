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
            $table->smallInteger('id_ano')->primary()->comment('Ano de exercicio');
            // Se você realmente precisar de timestamps na sua tabela de anos, o que é incomum, adicione:
            $table->timestamps();
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

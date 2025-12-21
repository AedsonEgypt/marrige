<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('padrinhos_cores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('descricao_cor')->nullable();
            $table->string('cor')->nullable();
            $table->string('cor_fonte')->default('#000000');
            $table->boolean('flg_selecionado')->default(FALSE);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('padrinhos_cores');
    }
};

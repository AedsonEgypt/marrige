<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('presentes', function (Blueprint $table) {
            $table->id();

            $table->string('nome')->nullable();
            $table->decimal('valor', 10, 2)->default(0);
            $table->boolean('vlr_simbolico')->default(false);
            $table->boolean('cha_panela')->default(0);

            $table->string('level')->nullable();
            $table->string('prioridade')->nullable();

            $table->decimal('vlr_processando', 10, 2)->default(0);
            $table->decimal('vlr_presenteado', 10, 2)->default(0);


            $table->string('name_img')->nullable();
            $table->string('img_url')->nullable();
            $table->boolean('flg_disponivel')->default(1);

            $table->string('selected_by_name')->nullable();
            $table->unsignedBigInteger('selected_by_user_id')->nullable();
            $table->dateTime('selected_at')->nullable();

            $table->string('path_img')->nullable();
            $table->text('descricao')->nullable();
            $table->string('tags')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presentes');
    }
};

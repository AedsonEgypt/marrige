<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_log', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->string('user_agent', 1000)->nullable();
            $table->longText('parameters')->nullable();
            $table->dateTime('dt_access')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_log');
    }
};

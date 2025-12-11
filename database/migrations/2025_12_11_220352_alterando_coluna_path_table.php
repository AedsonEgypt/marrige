<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->longText('img_url')->change();
        });
    }

    public function down(): void
    {
        Schema::table('presentes', function (Blueprint $table) {
            $table->string('img_url', 255)->change();
        });
    }
};

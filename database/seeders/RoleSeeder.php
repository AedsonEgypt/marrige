<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->upsert([
            ['id' => 1, 'description' => 'Admin'],
            ['id' => 2, 'description' => 'Padrinho'],
            ['id' => 3, 'description' => 'Convidado'],
        ], ['id']);
    }
}

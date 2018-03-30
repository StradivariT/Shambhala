<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ContextsTableSeeder extends Seeder {
    public function run() {
        DB::table('contexts')->insert([
            ['name' => 'Plan educativo'],
            ['name' => 'Curso'],
            ['name' => 'Grupo']
        ]);
    }
}
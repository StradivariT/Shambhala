<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->insert([
            ['email' => 'fer.vargas.torres@gmail.com', 'password' => '$2y$10$1k/B5Sb4i/s7Wwa3.kzrze7MbZ7R7XiSBRU.73kRyl1SEEbOFZx/K'],
            ['email' => 'gildatn@gmail.com',           'password' => '$2y$10$iEI/KfHx4pntqA5bgHFjK.7onrHqgUEvTm/t4z5Awu7Sz1VJLIMZS']
        ]);
    }
}
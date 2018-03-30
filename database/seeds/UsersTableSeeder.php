<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder {
    public function run() {
        DB::table('users')->insert([
            ['email' => 'fer.vargas.torres@gmail.com', 'password' => bcrypt('8reakthe5tr34k')],
            ['email' => 'gil_5140@hotmail.com',        'password' => bcrypt('santodio5')]
        ]);
    }
}
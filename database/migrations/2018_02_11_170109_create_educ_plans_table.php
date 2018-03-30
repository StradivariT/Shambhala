<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducPlansTable extends Migration {
    public function up()
    {
        Schema::create('educ_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();

            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('educ_plans'); }
}
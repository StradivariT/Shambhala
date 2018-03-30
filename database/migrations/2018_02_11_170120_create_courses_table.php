<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration {
    public function up() {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('educ_plan_id')->unsigned();
            $table->foreign('educ_plan_id')->references('id')->on('educ_plans')->onDelete('cascade');

            $table->string('name')->unique();
            $table->text('information')->nullable();

            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('courses'); }
}
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {
    public function up() {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
           
            $table->string('name')->unique();
            
            $table->string('participants_file_name')->nullable();
            $table->string('participants_file_storage')->nullable();
            
            $table->string('incidents_file_name')->nullable();
            $table->string('incidents_file_storage')->nullable();
            
            $table->string('evaluations_file_name')->nullable();
            $table->string('evaluations_file_storage')->nullable();
           
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('groups'); }
}
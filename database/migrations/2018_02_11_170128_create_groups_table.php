<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('course_id')->unsigned();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->string('name')->unique();
            $table->string('participants_file_name');
            $table->string('participants_file_storage');
            $table->string('incidents_file_name');
            $table->string('incidents_file_storage');
            $table->string('evaluations_file_name');
            $table->string('evaluations_file_storage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
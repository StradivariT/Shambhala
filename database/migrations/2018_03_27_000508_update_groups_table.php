<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('participants_file_name')->nullable()->change();
            $table->string('participants_file_storage')->nullable()->change();
            $table->string('incidents_file_name')->nullable()->change();
            $table->string('incidents_file_storage')->nullable()->change();
            $table->string('evaluations_file_name')->nullable()->change();
            $table->string('evaluations_file_storage')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContextResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('context_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('context_id')->unsigned();
            $table->foreign('context_id')->references('id')->on('contexts')->onDelete('cascade');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('context_resources', function($table) {
            $table->dropForeign('contexts_context_id_foreign');

            $table->foreign('context_id')->references('id')->on('contexts');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIncidentsToGroups extends Migration {
    public function up() {
        Schema::table('groups', function($table) {
            $table->text('incidents')->nullable();
        });
    }

    public function down() {
        Schema::table('groups', function($table) {
            $table->dropColumn('incidents');
        });
    }
}

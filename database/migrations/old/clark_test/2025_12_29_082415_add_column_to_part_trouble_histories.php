<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPartTroubleHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('part_trouble_histories', function (Blueprint $table) {
            $table->string('situation')->nullable()->after('status');
            $table->string('section')->nullable()->after('situation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('part_trouble_histories', function (Blueprint $table) {
            $table->dropColumn('situation');
            $table->dropColumn('section');
        });
    }
}

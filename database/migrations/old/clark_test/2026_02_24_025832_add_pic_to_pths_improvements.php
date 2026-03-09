<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPicToPthsImprovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pths_improvements', function (Blueprint $table) {
           $table->string('pic')->nullable()->after('counter_measure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pths_improvements', function (Blueprint $table) {
            $table->dropColumn('pic');
        });
    }
}

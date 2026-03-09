<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPthsImprovements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pths_improvements', function (Blueprint $table) {
           $table->string('factor')->nullable()->after('history_id');
           $table->string('cause')->nullable()->after('factor');
           $table->string('analysis')->nullable()->after('cause');
           $table->string('counter_measure')->nullable()->after('analysis');
           $table->string('implementation_date')->nullable()->after('counter_measure');
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
            $table->dropColumn('factor');
            $table->dropColumn('cause');
            $table->dropColumn('analysis');
            $table->dropColumn('counter_measure');
            $table->dropColumn('implementation_date');
        });
    }
}

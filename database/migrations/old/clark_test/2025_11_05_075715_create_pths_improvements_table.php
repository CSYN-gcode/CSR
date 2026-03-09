<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePthsImprovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pths_improvements', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('history_id')->comment('References part_trouble_histories.id');
            $table->foreign('history_id')
                ->references('id')
                ->on('part_trouble_histories')
                ->onDelete('cascade');

            $table->string('improvement_actions')->nullable();
            $table->string('remarks')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pths_improvements');
    }
}

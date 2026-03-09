<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePthsDefectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pths_defects', function (Blueprint $table) {
            $table->id();

            // Foreign key to main table
            $table->unsignedBigInteger('history_id')->comment('References part_trouble_histories.id');
            $table->foreign('history_id')
                ->references('id')
                ->on('part_trouble_histories')
                ->onDelete('cascade');

            // Link to master defect list
            $table->unsignedBigInteger('defect_id')->comment('References defects.id');
            $table->foreign('defect_id')
            ->references('id')
            ->on('defects')
            ->onDelete('restrict'); // prevents deleting a defect type if used

            // Defect-specific fields
            $table->text('illustration_of_defect')->nullable();
            $table->string('no_of_occurrence')->nullable();
            $table->string('root_cause')->nullable();

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
        Schema::dropIfExists('pths_defects');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartTroubleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_trouble_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - Pending, 2 - Done');
            $table->date('date_encountered')->nullable();
            $table->string('model')->nullable();
            $table->string('remarks')->nullable();

            // Define columns first
            $table->unsignedBigInteger('created_by')->nullable()->comment('References db_rapidx.id');
            $table->unsignedBigInteger('last_updated_by')->nullable()->comment('References db_rapidx.id');

            // Cross-database foreign key constraints
            $table->foreign('created_by')
                ->references('id')
                ->on('db_rapidx.users')
                ->onDelete('set null');

            $table->foreign('last_updated_by')
                ->references('id')
                ->on('db_rapidx.users')
                ->onDelete('set null');

            $table->softDeletes()->comment('0-Active, 1-Deleted');
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
        Schema::dropIfExists('part_trouble_histories');
    }
}

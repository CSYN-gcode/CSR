<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsrDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csr_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(1)->comment('1 - Pending, 2 - Done');
            $table->string('control_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('business_process')->nullable();
            $table->string('date_applied')->nullable();
            $table->string('revision_no')->nullable();
            $table->string('change_description')->nullable();
            $table->string('reviewed_by')->nullable();
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
        Schema::dropIfExists('csr_documents');
    }
}

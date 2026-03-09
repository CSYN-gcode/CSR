<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsrApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csr_approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('csr_id')->nullable()->comment('References db_csr.csr_documents.id');
            $table->string('approval_level')->nullable()->comment('1 - Section Head, 2 - QAS Validation');
            $table->string('status')->nullable()->comment('0 - Pending, 1 - Approved, 2 - Rejected');
            $table->text('remarks')->nullable();

            // Define columns first
            $table->unsignedBigInteger('created_by')->nullable()->comment('References db_rapidx.id');
            $table->unsignedBigInteger('last_updated_by')->nullable()->comment('References db_rapidx.id');

            // Cross-database foreign key constraints
            $table->foreign('csr_id')
                ->references('id')
                ->on('db_csr.csr_documents')
                ->onDelete('set null');

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
        Schema::dropIfExists('csr_approvals');
    }
}

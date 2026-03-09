<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsrEvidenceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csr_evidence_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evidence_id')->nullable()->comment('References db_csr.csr_evidences.id');
            // Defect-specific fields
            $table->text('file_name')->nullable();
            $table->text('original_name')->nullable();
            $table->string('remarks')->nullable();

            // Define columns first
            $table->unsignedBigInteger('created_by')->nullable()->comment('References db_rapidx.id');
            $table->unsignedBigInteger('last_updated_by')->nullable()->comment('References db_rapidx.id');

            // Cross-database foreign key constraints
            $table->foreign('evidence_id')
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
        Schema::dropIfExists('csr_evidence_files');
    }
}

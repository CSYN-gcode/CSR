<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToCsrAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csr_attachments', function (Blueprint $table) {
            $table->enum('file_type', ['pdf', 'excel'])->after('original_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csr_attachments', function (Blueprint $table) {
            $table->dropColumn([
                'file_type',
            ]);
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmFileShareLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_file_share_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_user_id');
            $table->unsignedBigInteger('share_id');
            $table->uuid('crm_file_uuid');
            $table->string('share_token', 255);
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
        Schema::dropIfExists('crm_file_shares');
    }
}

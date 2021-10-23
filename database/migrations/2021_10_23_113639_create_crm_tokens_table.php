<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crm_user_id');
            $table->longText('token');
            $table->timestamp('last_used_at');
            $table->timestamps();

            $table->foreign('crm_user_id')
                ->references('id')
                ->on('crm_users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_tokens');
    }
}

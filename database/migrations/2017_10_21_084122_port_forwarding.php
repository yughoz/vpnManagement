<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PortForwarding extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_forwading', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mikrotik_id');
            $table->string('mechine_name');
            $table->string('mechine_desc');
            $table->integer('status_code');
            $table->string('status_desc');
            $table->string('secret_name');
            $table->string('secret_password');
            $table->string('id_in_router');
            $table->string('chain');
            $table->string('action');
            $table->string('to-addresses');
            $table->integer('to-ports');
            $table->string('protocol');
            $table->integer('dst-port');
            $table->string('log');
            $table->string('log-prefix');
            $table->integer('bytes');
            $table->integer('packets');
            $table->string('invalid');
            $table->string('dynamic');
            $table->string('disabled');
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
        Schema::dropIfExists('port_forwading');
    }
}

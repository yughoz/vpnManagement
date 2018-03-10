<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGroupAccess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_access', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->string('module_code',25);
            $table->tinyInteger('createAcc');
            $table->tinyInteger('readAcc');
            $table->tinyInteger('updateAcc');
            $table->tinyInteger('deleteAcc');
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
        Schema::dropIfExists('group_access');
    }
}

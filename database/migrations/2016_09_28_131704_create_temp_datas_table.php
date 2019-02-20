<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTempDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid',60);
            $table->text('data');
            $table->unsignedInteger('company_id')->default(0);
            $table->timestamps();
            $table->index('uuid');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('temp_datas');
    }
}

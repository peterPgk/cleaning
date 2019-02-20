<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->default(0);
            $table->unsignedInteger('related_id')->default(0);
            $table->integer('service_category_id');
            $table->string('name');
            $table->string('description')->default('');
            $table->integer('limit')->default(1);
            $table->integer('prices_number')->default(1)->comment('How many prices we can have for this service');
            $table->timestamps();
            $table->softDeletes();
            $table->index('parent_id');
            $table->index('related_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}

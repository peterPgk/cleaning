<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('companies_services',function (Blueprint $table) {
           $table->increments('id');
           $table->unsignedInteger('company_id');
           $table->unsignedInteger('service_id');
           $table->decimal('price',10,2);
           $table->index('company_id');
           $table->index('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::drop('companies_services');
    }
}

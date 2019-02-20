<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('name')->default('');
            $table->string('trading_name');
            $table->string('client_name');
            $table->string('company_number');
            $table->string('vat');
            $table->string('phone')->default('');
            $table->string('phone_2')->default('');
            $table->string('website')->default('');
            $table->string('address')->default('');
            $table->string('address_2')->default('');
            $table->string('address_3')->default('');
            $table->string('description')->default('');
	        $table->string('city')->default('');
	        $table->string('postcode', 50)->default('');
            $table->text('complaints');
            $table->date('date_established');
            $table->boolean('liability')->default(0);
            $table->integer('liability_amount')->default(0);
            $table->string('liability_certificate')->nullable()->default(null);
            $table->date('liability_expires')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
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
        \Schema::drop('company_datas');
    }
}

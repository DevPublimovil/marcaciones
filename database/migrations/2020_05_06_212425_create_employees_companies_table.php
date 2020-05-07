<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees_companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departament_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->timestamps();

            $table->foreign('departament_id')->references('id')->on('departaments')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees_companies');
    }
}

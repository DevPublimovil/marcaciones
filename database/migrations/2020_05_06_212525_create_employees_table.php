<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name_employee',150);
            $table->string('surname_employee',150);
            $table->string('cod_marking',15)->nullable();
            $table->string('cod_terminal',15)->nullable();
            $table->decimal('salary',8,2)->nullable();
            $table->string('position',150)->nullable();
            $table->unsignedBigInteger('type_employee')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('jefe_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('departament_id')->nullable();
            $table->timestamps();
            
            $table->foreign('type_employee')->references('id')->on('employee_types')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('jefe_id')->references('id')->on('employees')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')
                ->onDelete('set null')
                ->onUpdate('cascade');
            $table->foreign('departament_id')->references('id')->on('departaments')
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
        Schema::dropIfExists('employees');
    }
}

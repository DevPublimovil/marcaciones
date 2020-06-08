<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markings', function (Blueprint $table) {
            $table->id();
            $table->string('serialno',25)->nullable();
            $table->string('cod_marking',15)->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->decimal('hours_worked',8,2)->nullable();
            $table->decimal('extra_hours',8,2)->nullable();
            $table->decimal('late_arrivals',8,2)->nullable();
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
        Schema::dropIfExists('markings');
    }
}

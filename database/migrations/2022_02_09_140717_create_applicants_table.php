<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('city');
            $table->date('dob');
            $table->string('email');
            $table->string('guardian');
            $table->bigInteger('mobile');
            $table->bigInteger('mobile2');
            $table->string('name');
            $table->string('image');
            $table->bigInteger('postalcode');
            $table->string('state');
            $table->string('slug');
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('applicants');
    }
}

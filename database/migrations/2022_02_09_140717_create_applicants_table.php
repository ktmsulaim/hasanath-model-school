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
            $table->string('name');
            $table->date('dob');
            $table->string('guardian');
            $table->text('address');
            $table->bigInteger('mobile');
            $table->tinyInteger('type')->default(0);
            $table->string('class');
            $table->string('mother');
            $table->string('image');
            $table->tinyInteger('brothers')->default(0);
            $table->tinyInteger('sisters')->default(0);
            $table->string('uuid')->unique();
            $table->date('dod_father')->nullable();
            $table->integer('status')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
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

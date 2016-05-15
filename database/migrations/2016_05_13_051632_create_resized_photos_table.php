<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResizedPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resized_photos', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('photo')->unsigned()->index()->nullable();
            $table->string('size', 20);
            $table->string('src')->nullable();
            $table->enum('status', ['new', 'in_progress', 'complete', 'error'])->default('new');
            $table->string('comment', 100)->nullable();
            $table->timestamps();
            $table->foreign('photo')->references('id')->on('photos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('resized_photos');
    }
}

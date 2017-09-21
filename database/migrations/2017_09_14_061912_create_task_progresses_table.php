<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_progresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('taskId');
            $table->text('message');
            $table->text('observation')->nullable();
            $table->boolean('read')->default(0);
            $table->integer('progress')->nullable();

            $table->integer('createdBy')->nullable();
            $table->integer('modifyBy')->nullable();

            $table->dateTime('readTime')->nullable();

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
        Schema::dropIfExists('task_progresses');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_ascii');
            $table->string('slug');
            $table->text('data');
            $table->integer('level');
            $table->string('cover_url');
            $table->string('type');
            $table->string('summary');
            $table->text('content');
            $table->integer('viewed');
            $table->tinyInteger('status');
            $table->datetime('created_at')->nullable()->default(null);
            $table->datetime('updated_at')->nullable()->default(null);
            $table->datetime('deleted_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lessons');
    }
}

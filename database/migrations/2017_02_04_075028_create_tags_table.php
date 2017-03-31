<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tags_name')->nullable();
            $table->string('tags_flag')->nullable();
            $table->timestamps();
        });
        Schema::create('posts_tags', function(Blueprint $table){
            $table->integer('posts_id')->unsigned()->index();
            $table->foreign('posts_id')->references('id')->on('posts')->onDelete('cascade');
            $table->integer('tags_id')->unsigned()->index();
            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
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
        Schema::dropIfExists('tags');
    }
}

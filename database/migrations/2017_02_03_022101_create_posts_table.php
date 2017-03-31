<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('flag')->unique();
            $table->string('title')->nullable();
            $table->string('thumb')->default('')->nullable();
            $table->integer('category_id')->default('0');
            $table->integer('user_id');
            $table->text('content')->nullable();
            $table->text('markdown')->nullable();
            $table->integer('views')->default('0')->nullable();
            $table->integer('comments')->default('0')->nullable();
            $table->string('ipaddress')->default('0.0.0.0')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('posts');
    }
}

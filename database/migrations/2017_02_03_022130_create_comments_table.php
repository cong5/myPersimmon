<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default('0')->nullable();
            $table->string('name')->default('')->nullable();
            $table->integer('posts_id')->default('0')->nullable();
            $table->string('email')->default('')->nullable();
            $table->string('url')->default('')->nullable();
            $table->text('content')->nullable();
            $table->text('markdown')->nullable();
            $table->string('ipaddress')->default('0.0.0.0')->nullable();
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
        Schema::dropIfExists('comments');
    }
}

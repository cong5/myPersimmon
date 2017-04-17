<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->string('option_title')->nullable();
            $table->string('option_name')->nullable();
            $table->text('option_value')->nullable();
            $table->string('option_group')->nullable();
            $table->string('option_remark')->nullable();
            $table->enum('option_status', ['base','extends','hidden'])->default('extends');
            $table->enum('data_type', ['textarea','text'])->default('text');
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
        Schema::dropIfExists('options');
    }
}

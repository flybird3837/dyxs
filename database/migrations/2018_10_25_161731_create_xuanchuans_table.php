<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateXuanchuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xuanchuans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id')->comment('党组织ID');
            $table->string('name', 50)->nullable()->comment('党支部名称');
            $table->string('category', 20)->nullable()->comment('类别');
            $table->string('intro')->nullable()->comment('简介');
            $table->string('video')->comment('视频');
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
        Schema::dropIfExists('xuanchuans');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDangyuansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dangyuans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('project_id');
            $table->string('name', 20)->comment('姓名');
            $table->boolean('sex')->default(true)->comment('性别');
            $table->date('in_time')->comment('入党时间');
            $table->string('image')->nullable()->comment('照片');
            $table->string('video')->nullable()->comment('视频');
            $table->string('audio')->nullable()->comment('语音');
            $table->string('hls_id')->nullable()->comment('hls处理id');
            $table->boolean('hls_status')->default(0)->comment('hls状态');
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
        Schema::dropIfExists('dangyuans');
    }
}

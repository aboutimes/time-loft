<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');    //标题
            $table->string('author');    //作者
            $table->text('content');    //正文
            $table->integer('read_number')->default(0);    //阅读数
            $table->integer('like')->default(0);    //喜欢数
            $table->integer('dislike')->default(0);    //不喜欢数
            $table->boolean('is_top')->default(false);    //置顶
            $table->integer('deleted_at')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
//            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}

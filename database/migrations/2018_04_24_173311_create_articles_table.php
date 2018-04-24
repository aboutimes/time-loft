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
            $table->text('content');    //正文
            $table->string('author');    //作者
            $table->integer('content');    //阅读数
            $table->integer('like');    //喜欢数
            $table->integer('dislike');    //不喜欢数
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

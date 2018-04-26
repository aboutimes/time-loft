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
            $table->integer('user_id')->unsigned()->nullable();     //用户
            $table->integer('category_id')->unsigned()->nullable();    //分类
            $table->string('title');    //标题
            $table->string('author')->nullable();    //作者，用于转载
            $table->boolean('is_reprint')->default(false);    //是否转载
            $table->string('reprint_url')->nullable();    //转载地址
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

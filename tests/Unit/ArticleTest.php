<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAllArticle()
    {
        $webHost = config('app.api_url');
        $user = \App\User::all()->random();
        $response = $this->actingAs($user,'api')
            //辅助函数 actingAs 为认证给定用户是 当前用户提供了简单的实现方法
            //->withoutMiddleware()绕过认证
            ->get("$webHost/v1/articles");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'title',
                        'author',
                        'is_reprint',
                        'reprint_url',
//                            'content',
                        'category',
                        'tag',
                        'footprints_count',
//                            'footprints',
                        'read_number',
                        'like',
                        'dislike',
                        'is_top',
                        'article_url',
                        'created_at' => [
                            'date',
                            'timezone_type',
                            'timezone'
                        ],
                        'updated_at' => [
                            'date',
                            'timezone_type',
                            'timezone'
                        ],
                        'deleted_at'
                    ]
                ],
            ]);
    }

    /**
     * test get one user.
     *
     * @return void
     */
    public function testOneArticle()
    {
        $webHost = config('app.api_url');
        $user = \App\User::all()->random();
        $articles = \App\Article::all()->random();
        $response = $this->actingAs($user,'api')
            ->get("$webHost/v1/article/$articles->id");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'title',
                    'author',
                    'is_reprint',
                    'reprint_url',
                    'content',
                    'category',
                    'tag',
                    'footprints_count',
                    'footprints' => [
                        '*' => [
                            'desc',
                            'lng',
                            'lat',
                            'created_at'
                        ]
                    ],
                    'read_number',
                    'like',
                    'dislike',
                    'is_top',
                    'article_url',
                    'created_at' => [
                        'date',
                        'timezone_type',
                        'timezone'
                    ],
                    'updated_at' => [
                        'date',
                        'timezone_type',
                        'timezone'
                    ],
                    'deleted_at'
                ],
            ]);
    }
}

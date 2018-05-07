<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * 函数必须以小写test开头
     * test get all user.
     *
     * @return void
     */
    public function testAllUser()
    {
        $webHost = config('app.api_url');
        $user = \App\User::all()->random();
        $response = $this->actingAs($user,'api')
            //辅助函数 actingAs 为认证给定用户是 当前用户提供了简单的实现方法
            //->withoutMiddleware()绕过认证
            ->get("$webHost/v1/users");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'email',
                        'mobile',
                        'description',
                        'last_login_ip',
                        'footprints_count',
                        'user_url',
                        'avatar_url',
                        'created_at',
                        'updated_at',
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
    public function testOneUser()
    {
        $webHost = config('app.api_url');
        $user = \App\User::all()->random();
        $response = $this->actingAs($user,'api')
            ->get("$webHost/v1/user/$user->id");
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'email',
                    'mobile',
                    'description',
                    'last_login_ip',
                    'articles_count',
                    'articles' => [
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
//                            'created_at',
//                            'updated_at',
//                            'deleted_at'
                        ]
                    ],
                    'footprints_count',
                    'footprints' => [
                        '*' => [
                            'desc',
                            'lng',
                            'lat',
                            'created_at'
                        ]
                    ],
                    'user_url',
                    'avatar_url',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
            ]);
    }
}

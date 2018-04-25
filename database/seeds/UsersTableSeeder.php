<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'admin',
            'email' => '247689829@qq.com',
            'mobile' => '13800000000',
            'description' => '这个人很懒，什么都没留下……',
            'last_login_ip' => '109.109.109.109',
            'password' => bcrypt('12345678'),
            'remember_token' => str_random(10),
        ]);
        factory(\App\User::class,50)->create();
    }
}

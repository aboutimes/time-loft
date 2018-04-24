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
            'mobile' => '13800000000',
            'description' => '这个人很懒，什么都没留下……',
            'password' => bcrypt('12345678')
        ]);
        factory(\App\User::class,5)->create();
    }
}

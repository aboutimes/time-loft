<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return mixed|string
     */
    public function username()
    {
        //兼容名字/邮箱/电话登陆，需要前端区分字段
        //扩展自vendor/laravel/framework/src/Illuminate/Foundation/Auth/AuthenticatesUsers.php
        $request = request();
        //过滤空值
        $request = array_filter($request->all());
        foreach (['name', 'mobile'] as $v) {
            if (array_key_exists($v, $request)) {
                return $v;
            }
        }
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }
}

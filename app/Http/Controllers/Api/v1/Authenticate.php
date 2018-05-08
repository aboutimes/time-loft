<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class Authenticate extends Controller
{

    public function __construct(){
        $this->middleware('guest')->except('logout');
//        $this->middleware('auth:api')->only(['logout']);
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
//        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
//            $user = Auth::user();
//            //createToken($user->name),括号中定义 oauth_access_tokens 名字
//            $success['token'] =  $user->createToken($user->name)->accessToken;
//            return response()->json(['success' => $success], 200);
//        }else {
//            return response()->json(['error'=>'Unauthorised'], 401);
//        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // confirmed 用于验证两次输入密码是否一致，对应字段 password_confirmation
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'regex:/(?!.*@)\D+/',
                'max:25',
                'unique:users'
            ],
            'email' => 'nullable|required_without:mobile|string|email|max:255|unique:users',
            'mobile' => [
                'nullable',
                'required_without:email',
                'string',
                'regex:/^1[2345789][0-9]{9}$/',
                'unique:users'
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => '请填写用户名',
            'name.string' => '用户名必须是字符串',
            'name.max' => '用户名长度必须小于 25',
            'name.regex' => '用户名不能包含 @，且不能为纯数字',
            'name.unique' => '用户名已被使用',
            'email.required_without' => '请填写邮箱或手机号码',
            'email.string' => '电子邮箱必须是字符串',
            'email.email' => '请填写正确的电子邮箱格式',
            'email.max' => '电子邮箱长度必须小于 255',
            'email.unique' => '电子邮箱已被使用',
            'mobile.required_without' => '请填写邮箱或手机号码',
            'mobile.string' => '手机号码必须是字符串',
            'mobile.regex' => '请输入正确的手机号码',
            'mobile.unique' => '手机号码已被使用',
            'password.required' => '请填写密码',
            'password.string' => '密码必须是字符串',
            'password.min' => '密码长度必须大于 8 位',
            'password.confirmed' => '两次输入的密码不一致'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $data = request()->only('name', 'email', 'mobile', 'password');
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email']??null,
            'mobile' => $data['mobile']??null,
            'password' => bcrypt($data['password']),
        ]);

        if (!$user) {
            return response()->json([
                'message' => '注册失败',
                'data' => null,
            ], 422);
        }
        return response()->json([
            'message' => '注册成功',
            'data' => $user
        ], 201);

//        //注册后自动登陆
//        //继承
//        use GuzzleHttp\Client;

//        $data['username'] = $data['email']??$data['mobile'];
//        $client = \Laravel\Passport\Client::where('password_client', 1)->first();
//        $http = new Client;
//
//        $response = $http->post($request->getHost().'/oauth/token', [
//            'form_params' => [
//                'grant_type' => 'password',
//                'client_id' => $client->id,
//                'client_secret' => $client->secret,
//                'username' => $data['username'],
//                'password' => $data['password'],
//                'scope' => '',
//            ],
//        ]);
//        return json_decode((string) $response->getBody(), true);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], 200);
    }

    /**
     * logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if (!Auth::guard('api')->check()) {
            return response([
                'message' => '没有活跃用户'
            ], 401);
        }
        //revoke or delete
        Auth::guard('api')->user()->token()->revoke();
        return response()->json([
            'message' => '成功退出登陆',
            'data' => null
        ],201);
    }
}
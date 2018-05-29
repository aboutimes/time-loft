<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ClientException;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Authenticate extends Controller
{

    public function __construct(){
        $this->middleware('auth:api')->only(['logout']);
    }


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
//        if(Auth::attempt(['email' => request('username'), 'password' => request('password')])) {
//            $user = Auth::user();
//            //createToken($user->name),括号中定义 oauth_access_tokens 名字
//            $success['token'] =  $user->createToken($user->name)->accessToken;
//            return response()->json(['success' => $success], 200);
//        }else {
//            return response()->json(['error'=>'Unauthorised'], 401);
//        }

//        [
//            'grant_type' => env('OAUTH_GRANT_TYPE'),
//            'client_id' => env('OAUTH_CLIENT_ID'),
//            'client_secret' => env('OAUTH_CLIENT_SECRET'),
//            'scope' => env('OAUTH_SCOPE', '*'),
//        ]
        $validator = Validator::make($request->all(), [
            'username'    => 'required|string|max:25',
            'password' => 'required|string',
        ], [
            'username.required' => '请填写用户名',
            'username.string' => '用户名必须是字符串',
            'username.max' => '用户名长度必须小于 25',
            'password.required' => '请填写密码',
            'password.string' => '密码必须是字符串'
        ]);


        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }
        $data = $request->only(['username','password']);

        $client = \Laravel\Passport\Client::where('password_client', 1)->first();
        $http = new Client;
        try {
            $response = $http->post($request->getHost().'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'scope' => '*',
                ],
            ]);
        }catch (ConnectException $e) {
            return $e->getResponse();
        }catch (ClientException $e) {
//            return $e->getResponse();
            return response()->json(['error'=>['password' => ['用户密码不正确']]], 401);
        }
        return json_decode((string) $response->getBody(), true);
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
            'username' => [
                'required',
                'string',
                'regex:/(?!.*@)\D+/',
                'max:25',
                'unique:users,name'
            ],
            'email' => 'nullable|required_without:mobile|string|email|max:255|unique:users',
            'mobile' => [
                'nullable',
                'required_without:email',
                'string',
                'regex:/^1[2345789][0-9]{9}$/',
                'unique:users'
            ],
            'password' => 'required|string|min:8|confirmed'
        ], [
            'username.required' => '用户名不能为空',
            'username.string' => '用户名必须是字符串',
            'username.max' => '用户名长度必须小于 25',
            'username.regex' => '用户名不能包含 @，且不能为纯数字',
            'username.unique' => '用户名已被使用',
            'email.required_without' => '邮箱或手机号码不能为空',
            'email.string' => '电子邮箱必须是字符串',
            'email.email' => '请填写正确的电子邮箱格式',
            'email.max' => '电子邮箱长度必须小于 255',
            'email.unique' => '电子邮箱已被使用',
            'mobile.required_without' => '请填写邮箱或手机号码',
            'mobile.string' => '手机号码必须是字符串',
            'mobile.regex' => '请输入正确的手机号码',
            'mobile.unique' => '手机号码已被使用',
            'password.required' => '密码不能为空',
            'password.string' => '密码必须是字符串',
            'password.min' => '密码长度必须大于 8 位',
            'password.confirmed' => '两次输入的密码不一致'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $data = request()->only('username', 'email', 'mobile', 'password');
//        dd($data);
//        exit();
        $user = User::create([
            'name' => $data['username'],
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
//        return response()->json([
//            'message' => '注册成功',
//            'data' => $user
//        ], 201);

//        //注册后自动登陆
//        //继承

        $data['username'] = $data['email']??$data['mobile'];
        $client = \Laravel\Passport\Client::where('password_client', 1)->first();
        $http = new Client;

        $response = $http->post($request->getHost().'/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $data['username'],
                'password' => $data['password'],
                'scope' => '*',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
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
//        if (!Auth::guard('api')->check()) {
//            return response([
//                'message' => '没有活跃用户'
//            ], 401);
//        }
        //revoke or delete
//        Auth::guard('api')->user()->token()->revoke();
        Auth::guard('api')->user()->token()->delete();
        return response()->json([
            'message' => '成功退出登陆',
            'data' => null
        ],201);
    }
}
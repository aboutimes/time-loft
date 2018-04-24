# 项目指南

## 1、安装
>环境需求
>PHP >= 7.0.0
>PHP OpenSSL 扩展
>PHP PDO 扩展
>PHP Mbstring 扩展
>PHP Tokenizer 扩展
>PHP XML 扩展
>web server (apache/nginx/...)
>composer
### 1.1、laravel 安装器安装
- 安装 laravel安装器``composer global require "laravel/installer"``；
- 安装 laravel``laravel new example``，安装最新版。

### 1.2、Composer Create-Project 命令安装
- 安装 laravel``composer create-project --prefer-dist laravel/laravel example --5.5.*``，版本号自选

## 2、laravel 配置
### 2.1、配置权限
web 服务器需要拥有 storage 目录下的所有目录和 bootstrap/cache 目录的写权限。
### 2.2、配置环境变量
复制 ``.env.example`` 重命名为 ``.env`` ，配置数据库和 APP_KEY 等
env 参数不能含有空格，如有必要，需将参数用双引号包围
### 2.3、生成 app_key
``php artisan key:generate``
### 2.4、nginx 配置
```none
server {
    listen 80;
    server_name example.com;
    root /项目位置/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }  
    location = /robots.txt  { access_log off; log_not_found off; }  

    #error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_intercept_errors on;
        include fastcgi_params;
        include fastcgi.conf;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }  
}
```
### 2.5、host 配置
设置域名为本地解析
```bash
127.0.0.1 example.com
```

## 3、用户认证
### 3.1、启用认证
``php artisan make:auth`` 
``php artisan migrate``
会生成 HomeController 、resources/views/layouts 以及 resources/views/auth 登陆视图
配置文件位于 config/auth.php 设置完成后，如果未登陆会重定向到登陆页面进行注册登陆
可以在 LoginController、RegisterController 和 ResetPasswordController 
中设置 redirectTo 属性来自定义重定向的位置
### 3.2、登陆认证字段
默认使用 email ，如需修改可以在 LoginController 里面定义一个 username 方法
```php
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
```
### 3.3、常用``Auth facade``方法
```php
use Illuminate\Support\Facades\Auth;

//是否登陆
if (Auth::check()) {
    // 用户已登录...
}
// 获取当前已认证的用户...
$user = Auth::user();

// 获取当前已认证的用户 ID...
$id = Auth::id();

//登出
Auth::logout();
```
### 3.4、找回密码
以163邮箱为例，需要在邮箱官网设置授权密码
然后配置 env 即可
```none
MAIL_DRIVER=smtp
MAIL_HOST=smtp.163.com
MAIL_PORT=25
MAIL_USERNAME=example
MAIL_FROM_NAME=example
MAIL_FROM_ADDRESS=example@163.com
MAIL_PASSWORD=****
MAIL_ENCRYPTION=null
```
## 4、Oauth2 认证
### 4.1、安装 passport
``composer require laravel/passport``
低版本 laravel 会出现版本不兼容，可指定较低版本
``composer require laravel/passport=~4.0``
### 4.2、注册 passport
在配置文件 config/app.php 的providers 数组中注册 Passport 服务
``Laravel\Passport\PassportServiceProvider::class,``
### 4.3、生成认证表
``php artisan migrate``
表文件位置（/vendor/laravel/passport/database）
### 4.4、生成加密密匙
生成安全访问令牌时所需的加密密钥 ``php artisan passport:install``
同时，这条命令也会创建用于生成访问令牌的「个人访问」客户端和「密码授权」客户端
```bash
Client ID: 1
Client Secret: AwDMcCs65rXkzF80wPaINx5fkoXEfa8lcuuPEvQK
Password grant client created successfully.
Client ID: 2
Client Secret: KLlLijWk3hX2Ntfzo2iFPgwT4GyITpBjEuDozp5H
```
### 4.5、User 继承 HasApiTokens
命令执行后，请将 Laravel\Passport\HasApiTokens 添加到 App\User 模型中，
这会给你的模型提供一些辅助函数，用于检查已认证用户的令牌和使用范围
```php
<?php
namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```
### 4.6、注册认证路由
在 AuthServiceProvider 的 boot 方法中调用 Passport::routes 函数
这个函数会注册发出访问令牌并撤销访问令牌、客户端和个人访问令牌所必需的路由
并在此设置令牌有效期和刷新时间，默认有效期为永久有效
```php
<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        //调用 Passport::routes 函数
        //定义 OAuth domain
        Passport::routes(null, ['domain' => config('app.api_url')]);
        //令牌有效期，Weeks,days,Hours,Minutes
        Passport::tokensExpireIn(Carbon::now()->addMinutes(60));
        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(120));
    }
}
```
### 4.7、配置看守器
最后，将配置文件 config/auth.php 中授权看守器 guards 的 api 的 driver 选项改为 passport。
此调整会让你的应用程序在在验证传入的 API 的请求时使用 Passport 的 TokenGuard 来处理
```php
<?php

    return [
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
        
            'api' => [
                'driver' => 'passport',
                'provider' => 'users',
            ],
        ],
    ];
```
## 5、多字段 api 认证
```php
//User 模型中增加判断，多字段检索效率会影响
//扩展自vendor/laravel/passport/src/Bridge/UserRepository.php
public function findForPassport($username)
{
    //兼容名字/邮箱/电话登陆，需要前端区分字段
    return $this->Where('email', $username)
        ->orWhere('name', $username)
        ->orWhere('mobile', $username)
        ->first();
}
```
## 6、api 配置子域名 http://api.example.com
### 6.1、修改 map
修改 app/Providers/RouteServiceProvider.php
```php
protected function mapApiRoutes()
    {
        //api 设置子域名路由
        Route::group([
            //config/app.php 增加 api_url 配置
            'domain' => config('app.api_url'),
            'middleware' => 'api',
            //api 控制器单独放在 Controllers\Api 目录
            'namespace' => $this->namespace.'\Api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
```
### 6.2、修改 config/app.php 增加 api_url 配置
```php
'api_url' => env('API_API_URL', 'api.example.com'),
```
### 6.3、修改 .env 增加 API_API_URL 配置
```
API_API_URL=api.example.com
```
## 6.4、配置 nginx
```bash
    #配置两个 server name
    server_name api.example.com example.com;
    # 处理跨域
    add_header 'Access-Control-Allow-Origin' '$http_origin';
    add_header 'Access-Control-Allow-Headers' 'Content-Type, Accept, Cookie, X-Requested-With, X-XSRF-TOKEN';
    add_header 'Access-Control-Allow-Methods' 'GET,POST,OPTIONS,PUT,DELETE';
    add_header 'Access-Control-Allow-Credentials' 'true';
```
## 6.5、配置 host 
设置 api 子域名为本地解析
```bash
127.0.0.1 api.example.com
```
## 6.6、设置路由
postman 访问 post 获取token
```none
$response = $http->post('http://api.example.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'password',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'username' => 'taylor@laravel.com',
        'password' => 'my-password',
        'scope' => '',
    ],
]);
```
```php
Route::group([
    'middleware'=>'auth:api',
], function () {
    //http://api.example.com/user 将返回当前用户
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
```
postman 设置
- 获取token
POST http://api.example.com/oauth/token
```
username:example@qq.com
password:12345678
grant_type:password
scope:*
client_id:2
client_secret:***
```
- 访问数据
GET http://api.example.com/v1/users
```
Authorization:Bearer access_token
X-Requested-With:XMLHttpRequest
```
X-Requested-With 参数是模拟 ajaxHttp 请求 token 错误时抛出401，不添加参数时返回 /login 页面

## 5、配置。。。

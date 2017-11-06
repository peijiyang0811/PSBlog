<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    public function index()
    {
        return view('blog.login');
    }

    public function login(Request $request)
    {
        $params = $request -> except('_token');
        $info = DB::table('account')
                            -> select('id', 'uuid', 'rule_id', 'status', 'user_name', 'user_avatar')
                            -> where('user_email', $params['email'])
                            -> where('user_password', passwordEncrypt($params['password'], 2))
                            -> first();
        if (!$info) return ['code' => 201, 'message' => '请检查用户名或密码是否正确'];
        if ($info -> status == 10) return ['code' => 201, 'message' => '对不起,您的账户已被管理员封禁'];
        // 验证成功
        $account = new \App\Http\Controllers\Admin\LoginController();
        $account -> addAdminLog($info -> uuid, 1);
        session(['home_user_id' => json_encode($info)]);
        return ['code' => 200];
    }

    public function register()
    {

        return view('blog.register');
    }

    public function newAccount(Request $request)
    {
        $params = $request -> except('_token');
        if (!preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/', $params['email'])) return ['code' => 201, 'message' => '邮箱格式错误'];
        if ($params['password'] != $params['rePwd']) return ['code' => 201, 'message' => '两次输入的密码不一致'];
        // 查询邮箱是否被注册
        $exists = DB::table('account') -> where('user_email', $params['email']) -> first();
        if ($exists) return ['code' => 201, 'message' => '该邮箱已被注册'];
        $insertData['user_email'] = $params['email'];
        $insertData['admin_password'] = $insertData['user_password'] = passwordEncrypt($params['password'], 2);
        $insertData['uuid'] = Uuid::uuid4() -> toString();
        $insertData['create_time'] = time();
        $insertData['user_name'] = $params['email'];
        $id = DB::table('account') -> insertGetId($insertData);
        if (!$id) return ['code' => 201, 'message' => '注册失败'];
        return ['code' => 200];
    }
    
    public function forget()
    {

        return view('blog.login');
    }

    public function out()
    {
        session(['home_user_id' => NULL]);
        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\AuthsController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Account;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    /**
     * @后台登录验证
     *
     * */
    public function getAvatar(Request $request)
    {
        if ($request -> input('type') == 1) {
            $info = DB::table('account')
                -> select('user_avatar', 'rule_id')
                -> where(['user_phone' => $request->input('user')])
                -> first();
        }
        if (empty($info)) return ['code' => 201, 'message' => '用户不存在'];
        // 用户存在,判断权限
        if (!AuthsController::checkRule($info -> rule_id)) return ['code' => 201, 'message' => '对不起,您没有权限登录后台'];
        if ($request -> input('type') == 1) {
            return ['code' => 200, 'data' => url($info -> user_avatar)];
        }
    }
    /**
     * @name 判断手机号密码是否正确
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-09-14
     *
     * */
    public function checkPass(Request $request)
    {
        $params = $request -> except('_token');
        $password = passwordEncrypt($params['pass'], 2);
        $info = DB::table('account')
                    -> select('id', 'uuid', 'rule_id', 'status', 'real_name', 'user_avatar')
                    -> where('user_phone', $params['user'])
                    -> where('admin_password', $password)
                    -> first();
        if (!$info) return ['code' => 201, 'message' => '用户名或密码不正确'];
        // 判断用户状态
        if ($info -> status != 1) return ['code' => 201, 'message' => '对不起,您的账号处于封禁状态'];
        // 判断权限
        if (AuthsController::checkRule($info -> rule_id) != 1) return ['code' => 201, 'message' => '对不起,您没有权限登录后台'];
        // 登陆成功,写redis 写后台登陆日志
        $this -> addAdminLog($info->uuid, 2);
        Cache::put('admin_user_id'.$info->id, json_encode($info), 15);
        //Redis::set('admin_user_id'.$info->id, json_encode($info), 900);// 缓存900s
        session(['admin_user_id'=>$info->id]);
        return ['code'=>200];
    }
    /**
     * @name 写后台登陆日志
     * @param string $user_id
     * @param string $type 1 前台 2 后台
     * @return boolean
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-09-14
     * */
    public function addAdminLog($user_uuid, $type)
    {
        $id = DB::table('login_log')
                        -> insertGetId([
                            'user_uuid'             => $user_uuid,
                            'user_agent'            => getAgentInfo(),
                            'request_ip'            => ip2long(getClientIp()),
                            'request_port'          => $_SERVER['REMOTE_PORT'],
                            'login_type'            => $type,
                            'request_area'          => getAreaByIp(getClientIp()),
                            'device'                => get_device_type(),
                            'device_system'         => getOS(),
                            'create_time'           => time()
                        ]);
        DB::update('update ps_account set login_count=login_count+1  where uuid = ?', [$user_uuid]);
        if (!$id) return false;
        return true;
    }
    /**
     * @name 退出登陆
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-09-14
     * */
    public function loginOut(Request $request)
    {
        $id = session('admin_user_id');
        Cache::forget('admin_user_id'.$id);
        //Redis::set('admin_user_id'.$id, NULL);// 缓存900s
        session(['admin_user_id' => null]);
        return redirect('/');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ValiPost;
use Illuminate\Http\Request;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class AccountController extends AdminBaseController
{
    private $_table = 'account';
    /**
     * @name 用户列表
     * @return view
     *
     * @author peijiyang
     * @date 2017-09-06
     * */
    public function userList()
    {
        $list = DB::table('account')
                    -> select('id', 'user_name', 'real_name', 'rule_id', 'user_email', 'user_avatar', 'user_phone', 'status', 'create_time')
                    -> orderBy('id', 'desc')
                    -> paginate(15);
        foreach ($list as $key => $value) {
            $list[$key] -> rules = $this -> getRules($value -> rule_id);
            $list[$key] -> create_time = date('Y-m-d H:i:s', $value -> create_time);
        }
        return view('admin.account.list', ['users' => $list]);
    }

    public function addView()
    {
        return view('admin.account.add');
    }

    public function addPost(ValiPost $request)
    {
        $params = $request->except('_token');
        // 先查询手机号是否被使用过
        $phone = DB::table($this ->_table)
                             -> select('id')
                             -> where ('user_phone', $params['user_phone'])
                             -> first();
        if ($phone) return back()->withInput()->with('error', '该手机号已被注册过');
        $email = DB::table($this ->_table)
            -> select('id')
            -> where ('user_email', $params['user_email'])
            -> first();
        if ($email) return back()->withInput()->with('error', '该邮箱已被注册过');
        $params['user_password'] = $params['admin_password'] = passwordEncrypt($params['user_phone']);
        if ($params['rule_id'] == 2) $params['rule_id'] = '1,2';
        $params['uuid'] = Uuid::uuid4() -> toString();
        $params['create_time'] = time();
        // 模型 create 方法添加数据失败
        // 报错 SQLSTATE[HY000]: General error: 1364 Field 'user_name' doesn't have a default value (SQL: insert into `ps_account` () values ())
        $id = DB::table($this->_table) -> insert($params);
        if (!$id) return back()->withInput()->with('error', '用户添加失败');
        return back() -> with('success', '添加新用户成功');
    }
    /**
     * @name 权限数值转换
     * @param string $rule_id
     * @return string
     *
     * @author peijiyang
     * @date 2017-09-11
     * */
    public function getRules($rule_id)
    {
        $rules = '';
        switch ($rule_id) {
            case '1':
                $rules = '会员';
                break;
            case '1,2':
                $rules = '会员 | 管理员';
                break;
            default:
                $rules = '会员';
                break;
        }
        return $rules;
    }
    /**
     * @name 用户编辑页面
     * @author peijiyang
     * @date 2017-09-11
     * */
    public function edit($id)
    {
        $user = DB::table('account')
                            -> select('id', 'user_avatar', 'real_name', 'user_name', 'user_password', 'admin_password', 'user_phone', 'status', 'user_email', 'rule_id')
                            -> where('id', $id)
                            -> first();
        $rule_arr = explode(',', $user -> rule_id);
        $user -> rule = $rule_arr[1];
        return view('admin.account.edit', ['user' => $user]);
    }
    /**
     * @name 更新用户信息
     * @author peijiyang
     * @date 2017-09-11
     * */
    public function update(ValiPost $request)
    {
        $params = $request -> except('_token');
        $id = $params['id'];
        $old = $params['old_avatar'];
        unset($params['id']);
        unset($params['old_avatar']);
        if (empty($params['user_password'])) {
            unset($params['user_password']);
        } else {
            $params['user_password'] = passwordEncrypt($params['user_password']);
        }
        if (empty($params['admin_password'])) {
            unset($params['admin_password']);
        } else {
            $params['admin_password'] = passwordEncrypt($params['admin_password']);
        }
        if ($params['rule_id'] == 2) $params['rule_id'] = '1,2';
        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $params['user_avatar'] = '/storage/app/'. $request -> file('avatar') -> store('images/avatar');
                if ($old != '/storage/app/images/avatar/default.png' && file_exists(base_path($old))) {
                    unlink(base_path($old));
                }
                unset($params['avatar']);
            }
        }
        DB::beginTransaction();
        try {
            $row = DB::table('account')
                            -> where('id', $id)
                            -> update($params);
            DB::commit();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            $this -> log($e ->getMessage(), [], 'pdo');
        }
        if (empty($row)) return back() -> withInput() -> with('error', '更新失败,或者没有数据被更新');
        // 更新用户缓存
        $info = DB::table('account')
            -> select('id', 'rule_id', 'status', 'real_name', 'user_avatar')
            -> where('id', $id)
            -> first();
        Cache::put('admin_user_id'.$id, json_encode($info), 15);
        return redirect('admin/user/list');
    }
    /**
     * @name 删除用户信息
     * @author peijiyang
     * @date 2017-09-11
     * */
    public function delete(Request $request)
    {
        $id = $request -> input('id');
        if (!$id) return ['code' => 201, 'message'=>'非法操作'];
        $row = DB::table('account')
                        -> where('id', $id)
                        -> delete();
        if (!$row) return ['code' => 201, 'message'=>'删除失败'];
        return ['code' => 200];
    }

}

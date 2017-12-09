<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Account;
use Illuminate\Support\Facades\Redis;

class MenuController extends AdminBaseController
{

    /*
     * @name 后台菜单管理列表
     *
     * @author peijiang
     * @date 2017-09-22
     * */
    public function admin()
    {
        $list = DB::table('navigate')
            -> select('id', 'title', 'type', 'url', 'pid', 'icon', 'status', 'admin_id', 'create_time', 'edit_admin_id', 'update_time')
            -> orderBy('id', 'desc')
            -> paginate(10);
        if ($list) {
            foreach ($list as $key => $item) {
                $list[$key] ->create_time = date('Y-m-d H:i:s', $item -> create_time);
                $list[$key] ->update_time = date('Y-m-d H:i:s', $item -> update_time);
                $admin = json_decode(Cache::get('admin_user_id'.session('admin_user_id')));
                $list[$key] ->admin_name = $admin -> real_name;
                $list[$key] ->type_name = '前台';
                if ($item -> type == 2) $list[$key] ->type_name = '后台';
                $list[$key] ->edit_name = '';
                if ($item -> edit_admin_id) {
                    $info = DB::table('account')
                        -> select('real_name')
                        -> where('id', $item -> edit_admin_id)
                        -> first();
                    $list[$key] ->edit_name = $info -> real_name;
                }
                $list[$key] -> p_name = '一级分类';
                if ($item -> pid) {
                    $name = $info = DB::table('navigate')
                        -> select('title')
                        -> where('id', $item -> pid)
                        -> first();
                    $list[$key] -> p_name = $name -> title;
                }
            }
        }
        return view('admin.menu.admin', ['list' => $list]);
    }
    /*
     * @name 后台菜单添加操作
     *
     * @author peijiang
     * @date 2017-09-22
     * */
    public function addAdminMenu(Request $request)
    {
        $user_id = session('admin_user_id');
        if (!Cache::has('admin_user_id'.$user_id)) return ['code' => 201, 'message' => '登陆信息已失效,请重新登陆'];
        $title = DB::table('navigate')
                            -> select('id')
                            -> where('title', $request -> input('title'))
                            -> where('type', $request -> input('type'))
                            -> first();
        if ($title) return ['code' => 201, 'message' => '该标题已存在'];
        $url = DB::table('navigate')
            -> select('id')
            -> where('title', $request -> input('url'))
            -> where('type', $request -> input('type'))
            -> first();
        if ($url) return ['code' => 201, 'message' => '该链接已存在'];
        $params = [];
        foreach ($request -> except('_token') as $key => $val) {
            if (!is_null($val)) $params[$key] = $val;
        }
        $params['admin_id'] = $user_id;
        $params['create_time'] = time();
        $id = DB::table('navigate')
                -> insertGetId($params);
        if (!$id) return ['code' => 201, 'message' => '添加数据失败'];
        return ['code' => 200, 'message' => '添加数据成功'];
    }
    /*
     * @name 后台菜单状态改变
     *
     * @author peijiang
     * @date 2017-09-22
     * */
    public function updateAdmin(Request $request)
    {
        $user_id = session('admin_user_id');
        if (!Cache::has('admin_user_id'.$user_id)) return ['code' => 201, 'message' => '登陆信息已失效,请重新登陆'];
        $params = $request -> except('_token');
        $type = empty($params['type']) ? 1 : $params['type'];
        $id = $params['navId'];
        $update['status'] = $type;
        $res = DB::table('navigate')
                        -> where('id', $id)
                        -> update($update);
        if (!$res) return ['code' => 201, 'message' => '更新失败或没有被改变的数据'];
        return ['code' => 200];
    }
    /*
     * @name 后台菜单删除
     *
     * @author peijiang
     * @date 2017-09-22
     * */
    public function deleteAdmin(Request $request)
    {
        $user_id = session('admin_user_id');
        if (!Cache::has('admin_user_id'.$user_id)) return ['code' => 201, 'message' => '登陆信息已失效,请重新登陆'];
        // 先查询是否有 子列表数据,若有子列表,则不允许删除
        $id = $request -> input('navId');
        $child = DB::table('navigate')
                                -> select('id')
                                -> where('pid', $id)
                                -> first();
        if ($child) return ['code' => 201, 'message' => '该链接下面有自连接,拒绝删除'];
        $row = DB::table('navigate')
                            -> where('id', $id)
                            -> delete();
        if (!$row) return ['code' => 201, 'message' => '删除失败'];
        return ['code' => 200];
    }
    /*
     * @name 后台菜单编辑(更改类型,Icon,链接)
     *
     * @author peijiang
     * @date 2017-09-27
     * */
    public function edit($id)
    {
        $links = DB::table('navigate')
                                -> select('id','title', 'icon', 'type', 'url', 'pid')
                                -> where('id', $id)
                                -> first();
        return view('admin.menu.adminEdit', ['links' => $links]);
    }
    /*
     * @name 后台菜单执行更新操作(更改类型,Icon,链接)
     *
     * @author peijiang
     * @date 2017-09-27
     * */
    public function update(Request $request)
    {
        $params = $request -> except('_token');
        $id = $params['id'];
        $pid = $params['pid'];
        unset($params['id']);
        /*$info = DB::table('navigate')
                        -> select('id','title', 'icon', 'type', 'url', 'pid')
                        -> where('id', $pid)
                        -> where('type', $params['type'])
                        -> first();
        if ($info) return back() -> withInput() -> with('error', '该分类下有子连接,拒绝更改');*/
        $row = DB::table('navigate')
                        -> where('id', $id)
                        -> update($params);
        if (!$row) return back() -> withInput() -> with('error', '编辑失败或者没有数据被更新');
        return back() -> withInput() -> with('success', '编辑成功');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LinksPost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Links;
/**
 * $name 友情链接管理
 * */
class LinksController extends AdminBaseController
{
    /**
     * @name 友情链接首页
     * @param Request $request
     * @param Links $links
     * @return view
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-12-09
     **/
    public function index(Request $request, Links $links)
    {
        $data = $links -> orderBy('links.id', 'desc')
                        -> select('links.id', 'links.title', 'links.site_url', 'links.site_logo', 'links.contact_name', 'links.contact_qq', 'links.contact_phone', 'links.contact_name','links.status', 'links.create_time', 'account.real_name')
                        -> join('account', 'account.id', '=', 'links.admin_id')
                        ->paginate(10);

        return view('admin.links.index', ['links'=>$data]);
    }

    /**
     * @name 添加新链接
     * @return view
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-12-09
     **/
    public function add()
    {
        return view('admin.links.add');
    }

    /**
     * @name 执行添加动作
     * @param LinksPost $request 自定义的验证器
     * @param Links $links 数据模型
     * @return view
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date
     **/
    public function new(LinksPost $request, Links $links)
    {
        $params = $request -> except('_token');
        $links -> title = $params['title'];
        $links -> site_url = $params['site_url'];
        $links -> contact_name = $params['contact_name'];
        $links -> contact_qq = $params['contact_qq'];
        $links -> contact_phone = $params['contact_phone'];
        $links -> admin_id = session('admin_user_id');
        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                if (!storage_path('/app/images/logo')) {
                     mkdir(storage_path('/app/images/logo'));
                }
                $links -> site_logo = '/storage/app/'. $request -> file('avatar') -> store('images/logo');
            }
        }
        if (!$links -> save()) {
            return back()->withInput()->with('error', '添加失败');
        } else {
            return back() -> with('success', '添加成功');
        }
    }

    /**
     * @name 编辑页面
     * @param int $id id主键
     * @param Links $links
     * @return view
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date
     **/
    public function edit($id, Links $links)
    {
        $model = $links -> where('id', $id) -> first();
        return view('admin.links.edit', ['link' => $model]);
    }

    /**
     * @name 更新数据
     * @param
     * @return
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date
     **/
    public function update(Request $request, Links $links)
    {
        $params = $request -> except('_token');
        $model = $links -> where('id', $params['id']) -> first();
        $old = $params['old_logo'];
        if ($request->hasFile('avatar')) {
            if ($request->file('avatar')->isValid()) {
                $model -> site_logo = '/storage/app/'. $request -> file('logo') -> store('images/logo');
                if (file_exists(base_path($old))) {
                    unlink(base_path($old));
                }
            }
        }
        $model -> title = $params['title'];
        $model -> site_url = $params['site_url'];
        $model -> contact_name = $params['contact_name'];
        $model -> contact_qq = $params['contact_qq'];
        $model -> contact_phone = $params['contact_phone'];
        $model -> status = $params['status'];
        $row = $model -> save();
        if (!$row) return back()->withInput()->with('error', '修改失败');
        return redirect('admin/links/index');;
    }
}

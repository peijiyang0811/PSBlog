<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Account;

class IndexController extends AdminBaseController
{
    public function index() {
        // 后台首页
        return view('admin.index');
    }
    /**
     * @name 查询服务器状态
     * @return json
     *
     * @author peijiyang
     * @date 2017-09-05
     * */
    public function sysStatus()
    {
        $sysStatus = getSysStatus();
        return response()->json($sysStatus);
    }
}

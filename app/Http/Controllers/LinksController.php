<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Service\Log;
use App\Http\Controllers\Controller;

class LinksController extends Controller
{
    /*
     * @name 获取菜单链接数据
     *
     * @author peijiang
     * @date 2017-09-22
     * */
    public function getLinks(Request $request)
    {
        $info = DB::table('navigate')
                        -> select('id', 'title', 'icon', 'url', 'pid')
                        -> where('type', $request -> input('type'))
                        -> where('status', 1)
                        -> get();
        $data = [];
        if ($info) {
            foreach ($info as $key => $item) {
                $data[$key]['id'] = $item -> id;
                $data[$key]['title'] = $item -> title;
                $data[$key]['icon'] = $item -> icon;
                $data[$key]['url'] = $item -> url;
                $data[$key]['pid'] = $item -> pid;
            }
            $data = listToTree($data, 'pid');
        }
        return ['code' => 200, 'data' => $data];
    }

    public function getOneLinks()
    {
        //-> where('type', 2)
        $info = DB::table('navigate')
            -> select('id', 'title')
            -> where('pid', 0)
            -> get();
        $data = [];
        if ($info) {
            foreach ($info as $key => $item) {
                $data[$key]['id'] = $item -> id;
                $data[$key]['title'] = $item -> title;
            }
        }
        return ['code' => 200, 'data' => $data];
    }
}

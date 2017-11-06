<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ValiPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ArticleCateController extends AdminBaseController
{

    //获取所有商品分类信息 作用  只操作一次数据库  获取分类信息
    public static function getPageAll(Request $request)
    {
        $cates = DB::table('article_category')
            ->select('id','title','pid','path','create_time','admin_id', 'status', 'update_time')
            ->where(function($query) use($request){
                if($request->input('keywords')){
                    $query->where('title','like','%'.$request->input('keywords').'%');
                }
            })
            ->orderBy(DB::raw('concat(path,",",id)'))
            ->paginate(10);
        //$cates  是分页+数据  对象
        return $cates;
    }
    //格式化分类信息  让它 显示 层级  |----|----|-----
    public static function getPageFormatCate($arr)
    {
        $cates = $arr;
        //给分类信息添加上层级显示

        foreach($cates as $k=>$v)
        {
            $len = count(explode(',', $v -> path))-1;//顶级分类不添加|----
            $cates[$k]->title = str_repeat('|----', $len).$v->title;
            $cates[$k]->p_name = '一级分类';
            if ($v->pid) {
                $name = DB::table('article_category') -> select('title') -> where('id', $v -> pid) -> first();
                $cates[$k]->p_name = $name -> title;
            }
        }
        return $cates;
    }

    //商品分类显示页面 + 搜索 分页
    public function getIndex(Request $request)
    {
        //获取格式化后的分类数据
        $cates = self::getPageAll($request);
        //分类数据
        $arr = $cates -> items();
        //格式化商品分类数据
        $data = self::getPageFormatCate($arr);
        foreach ($data as $k=>$v){
            if($v->create_time){
                $data[$k]->create_time = date('Y-m-d H:i:s', $v->create_time);
            }else{
                $data[$k]->create_time = 'null';
            }
            if($v->update_time){
                $data[$k]->update_time = date('Y-m-d H:i:s', $v->update_time);
            }else{
                $data[$k]->update_time = 'null';
            }
            $name = DB::table('account') -> select('real_name') -> where('id', $v -> admin_id) -> first();
            $data[$k]->admin_name = $name -> real_name;
        }
        //传递搜索参数到模板
        //解析后台首页模板
        return view('admin.cate.index',[
            'data'          =>$data,
            'request'       =>$request->all(),
            'link'          => $cates
        ]);
    }


//===============================  分类添加  ================================
    public static function getAll()
    {
        $cates = DB::table('article_category')
            ->select('id','title','pid','path','create_time','admin_id')
            ->orderBy(DB::raw('concat(path,",",id)'))
            ->get();
        return $cates;
    }
    //格式化分类信息  让它 显示 层级  |----|----|-----
    public static function getFormatCate()
    {
        $cates = self::getAll();
        //给分类信息添加上层级显示
        foreach($cates as $k=>$v)
        {
            $len = count(explode(',',$v->path))-1;//顶级分类不添加|----
            $cates[$k]->title = str_repeat('|----',$len).$v->title;
        }
        return $cates;
    }
    //分类添加页面
    public function getAdd()
    {
        //获取格式化后的分类数据
        $cates = self::getFormatCate();
        //解析模板分配数据
        return view('admin.cate.add',['cate'=>$cates]);

    }
    //分类添加页面
    public function getEdit($id)
    {
        $data = DB::table('article_category') -> select('path', 'title', 'status', 'pid', 'id') -> where('id', $id) -> first();
        //获取格式化后的分类数据
        $cates = self::getFormatCate();
        //解析模板分配数据
        return view('admin.cate.edit',['cate'=>$cates, 'data' => $data]);

    }
    //新的分类信息添加到数据库
    public function postInsert(Request $request)
    {
        //id  fname pid path
        $data = $request->except('_token');

        //创建时间
        $data['create_time'] = time();
        $data['admin_id'] = session('admin_user_id');//登陆者的用户名
        $parent = DB::table('article_category')->select('path')->where('id', $data['pid'])->first();
        $data['path'] = $data['pid'];
        if ($data['pid']) {
            $data['path'] = $parent -> path .',' . $data['pid'];
        }
        //插入数据
        $res = DB::table('article_category')->insert($data);
        if($res) {
            //插入成功 返回列表页面  并带回去一个添加成功的信息
            return redirect('/admin/cate/add')->with('success','添加文章分类成功');
        } else {
            return back()->withInput()->with('error','文章分类添加失败');
        }
    }
    //================ 删除分类 =====================
    public function getDel(Request $request)
    {
        $data= $request->except('_token');
        $id = $data['id'];
        //查询 父类id 为 id 的 条件是否存在,若存在 则不能删除
        $exis = DB::table('article_category')->where('id', $id)->first();
        if (!$exis) return ['code' => 201, 'message' => '该分类不存在'];
        $res1 = DB::table('article_category')->where('pid', $id)->first();
        if ($res1) return ['code' => 201, 'message' => '该分类下还有子分类禁止删除'];
        // 该分类下是否有文章
        $res2 = DB::table('article')->where('cate_id', $id)->first();
        if ($res2) return ['code' => 201, 'message' => '该分类下还有文章禁止删除'];
        // 该 分类下没有子分类 并且 改分类下 没有商品存在 则 可以 删除数据
        $row = DB::table('article_category')->where('id', $id)->delete();
        if (!$row) return ['code' => 201, 'message' => '分类删除失败'];
        return ['code' => 200];
    }

    // ================ 更新分类 ===================
    public function getUpdate(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        $title = $data['new_title'];
        $pid = $data['pid'];
        //查看当前分类的 父级分类下 是否有 同名 分类  ,若有  则不允许添加
        $res1 = DB::table('article_category')->where('pid', $pid)->where('title', $title)->first();
        if ($pid != $data['old_pid']) {
            if($res1) return back() -> withInput() -> with('error', '查看当前分类的父级分类下有同名分类,拒绝修改');
        }
        $path = explode(',', $data['path']);
        if (count($path) > 1) {
            if ($pid != 0) {
                array_pop($path);
                array_push($path, $pid);
            }
        } else {
            // 原来是一级分类,若他的选择不是一级分类
            if ($pid != 0) array_push($path, $pid);
        }
        $path = implode(',', $path);
        //执行跟新分类名操作
        $res2 = DB::table('article_category')
            ->where('id', $id)
            ->update(['path' => $path,'title' => $title, 'pid' => $pid, 'status' => $data['status'], 'update_time' => time()]);
        if(!$res2) return back() -> withInput() -> with('error', '修改失败');
        return redirect('/admin/cate/list') -> with('success', '修改成功');
    }
}

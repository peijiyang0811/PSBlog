<?php
namespace App\Http\Controllers\Blog;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function center() {

        return view('blog.user.center');
    }

    public function article() {
        $user = json_decode(session('home_user_id'));
        $article = DB::table('article')
                        -> join('account', 'account.uuid', '=', 'article.user_uuid')
                        -> join('article_category', 'article.cate_id', '=', 'article_category.id')
                        -> select('article_category.title as cate_title', 'account.user_name', 'article.update_time', 'article.id', 'article.article_uuid', 'article.tag_ids', 'article.cate_id', 'article.title', 'article.subtitle', 'article.visit_count', 'article.vote_count', 'article.collect_count', 'article.status', 'article.image', 'article.is_open')
                        -> where('article.user_uuid', $user -> uuid)
                        -> orderBy('article.id', 'desc')
                        -> paginate(8);
        return view('blog.user.article', ['articles' => $article]);
    }

    public function addArticle()
    {
        //获取格式化后的分类数据
        $cates = self::getFormatCate();
        return view('blog.user.addBlog', ['cate' => $cates]);
    }
    public static function getAll()
    {
        $cates = DB::table('article_category')
            -> select('id','title','pid','path','create_time','admin_id')
            -> where('status', 1)
            -> orderBy(DB::raw('concat(path,",",id)'))
            -> get();
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
    /*编辑博客界面*/
    public function edit($article_uuid) {
        //获取格式化后的分类数据
        $cates = self::getFormatCate();
        $article = DB::table('article')
                                    -> select('id', 'title', 'subtitle', 'markdown', 'image', 'is_open', 'cate_id')
                                    -> where('article_uuid', $article_uuid)
                                    -> first();
        if (!$article) return redirect('/');
        return view('blog.user.edit', ['cate' => $cates, 'article' => $article]);
    }
    public function collect() {

        return view('blog.user.collect');
    }
    public function message() {

        return view('blog.user.message');
    }
}

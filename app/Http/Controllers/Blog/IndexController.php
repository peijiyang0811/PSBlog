<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        // 查询文章
        $article = DB::table('article')
                            -> join('account', 'account.uuid', '=', 'article.user_uuid')
                            -> join('article_category', 'article.cate_id', '=', 'article_category.id')
                            -> select('article_category.title as cate_title', 'account.user_name', 'article.update_time', 'article.is_open', 'article.article_uuid', 'article.tag_ids', 'article.cate_id', 'article.title', 'article.subtitle', 'article.visit_count', 'article.vote_count', 'article.collect_count', 'article.status', 'article.image', 'article.is_open')
                            -> where('article.is_open', 1)
                            -> where('article.status', 4)
                            -> orderBy('article.update_time', 'desc')
                            -> orderBy('article.recommend', 'desc')
                            -> orderBy('article.collect_count', 'desc')
                            -> orderBy('article.visit_count', 'desc')
                            -> paginate(10);
        /*$pics = DB::table('article')
                        -> join('article_category', 'article.cate_id', '=', 'article_category.id')
                        -> select('article_category.title as cate_title', 'article.article_uuid', 'article.image', 'article.title', 'article.subtitle', 'article.create_time')
                        -> orderBy('article.collect_count', 'desc')
                        -> orderBy('article.recommend', 'desc')
                        -> limit(5)
                        -> get();
        , 'pictures' => $pics
        */
        return view('blog.index', ['article' => $article]);
    }
}

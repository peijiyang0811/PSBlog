<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Service\MarkdownParser;
use Ramsey\Uuid\Uuid;

class ArticleController extends Controller
{

    /**
     * @name linux模块列表
     * @return
     *
     * @author peijiyang
     * @date 2017-09-30
     * */
    public function linuxIndex()
    {

        return view('blog.article.linuxList');
    }
    /**
     * @name 添加新博客数据操作
     * @return
     * @author peijiyang
     * @date 2017-09-28
     * */
    public function addBlog(Request $request)
    {
        $params = $request -> except('_token');
        /*$params['contents'] = $params['my-editormd-html-code'];
        unset($params['my-editormd-html-code']);*/
        $params['contents'] = $this -> markdownToHtml($params['markdown']);
        if ($request->hasFile('head_image')) {
            if ($request->file('head_image')->isValid()) {
                $params['image'] = '/storage/app/'. $request -> file('head_image') -> store('article');
            }
        }
        unset($params['head_image']);
        $user = json_decode(session('home_user_id'));
        $params['user_uuid'] = $user -> uuid;
        $params['update_time'] = $params['create_time'] = time();
        $params['article_uuid'] = Uuid::uuid4() -> toString();
        $id = DB::table('article') -> insertGetId($params);
        if (!$id) return back() -> withInput() -> with('error', '添加失败');
        return redirect(url('/center/blog')) -> with('success', '添加成功');
    }
    private function markdownToHtml($markdown)
    {
        // 正则匹配到全部的iframe
        preg_match_all('/&lt;iframe.*iframe&gt;/', $markdown, $iframe);
        // 如果有iframe 则先替换为临时字符串
        if (!empty($iframe[0])) {
            $tmp = [];
            // 组合临时字符串
            foreach ($iframe[0] as $k => $v) {
                $tmp[] = '【iframe'.$k.'】';
            }
            // 替换临时字符串
            $markdown = str_replace($iframe[0], $tmp, $markdown);
            // 讲iframe转义
            $replace = array_map(function ($v){
                return htmlspecialchars_decode($v);
            }, $iframe[0]);
        }
        // markdown转html
        $parser = new MarkdownParser();
        $html = $parser->makeHtml($markdown);
        $html = str_replace('<code class="', '<code class="language-', $html);
        // 将临时字符串替换为iframe
        if (!empty($iframe[0])) {
            $html = str_replace($tmp, $replace, $html);
        }
        return $html;
    }
    /**
     * @name 修改博客数据
     * 
     * @author peijiyang
     * @date 2017-9-30
     * */
    public function editBlog(Request $request)
    {
        $params = $request -> except('_token');
        $params['contents'] = $this -> markdownToHtml($params['markdown']);
            /*$params['contents'] = $params['my-editormd-html-code'];
            unset($params['my-editormd-html-code']);*/
        $old_image = $params['old_image'];
        $id = $params['id'];
        unset($params['id']);
        unset($params['old_image']);
        if ($request->hasFile('new_head_image')) {
            if ($request->file('new_head_image')->isValid()) {
                $params['image'] = '/storage/app/'. $request -> file('new_head_image') -> store('article');
                if (!empty($old_image) && file_exists(base_path($old_image))) unlink(base_path($old_image));
            }
            unset($old_image);
            unset($params['new_head_image']);
        }
//        $markdown = new MarkdownParser();
//        $params['contents'] = $markdown -> makeHtml($params['markdown']);
        $params['update_time'] = time();
        $row = DB::table('article')
                -> where('id', $id)
                -> update($params);
        if (!$row) return back() -> withInput() -> with('error', '修改失败');
        return redirect('/center/blog') -> with('success', '修改成功');
    }
    /**
     * @name 博客详情
     *
     * @author peijiyang
     * @date 2017-9-30
     * */
    public function detail($article_uuid)
    {
        $article = DB::table('article')
                                -> join('account', 'account.uuid', '=', 'article.user_uuid')
                                -> join('article_category', 'article_category.id', '=', 'article.cate_id')
                                -> select('article.*', 'article_category.title as cate_title','account.user_name')
                                -> where('article.article_uuid', $article_uuid)
                                -> first();
        if (!$article) {
            return ['error' => 404];
        }
        // 上一篇
        $prev = $this -> getPrevArt($article -> id);
        // 下一篇
        $next = $this->getNextArt($article -> id);
        // 评论
        $comments = DB::table('comments')
                                    -> join('account', 'account.uuid', '=', 'comments.user_uuid')
                                    -> select('account.user_name', 'account.user_avatar', 'comments.parent_id', 'comments.at_user_uuid', 'comments.user_uuid','comments.id', 'comments.contents', 'comments.create_time')
                                    -> where('article_uuid', $article_uuid)
                                    -> orderBy('comments.create_time', 'desc')
                                    -> get();
        foreach ($comments as $key => $comment) {
            $comments[$key] -> at_user_name = '';
            if ($comment -> at_user_uuid) {
                $user = DB::table('account') -> select('user_name') -> where('uuid', $comment -> at_user_uuid) -> first();
                $comments[$key] -> at_user_name = $user -> user_name;
            }
        }
        DB::table('article') -> where('article_uuid', $article_uuid) -> increment('visit_count');
        return view('blog.article.detail', ['article' => $article, 'comments' => $comments, 'prev' => $prev, 'next' => $next]);
    }

    /**
     * @name 获取上一篇文章
     * @param $id string 文章id
     * @return object|false
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date
     **/
    private function getPrevArt($id)
    {
        if ($id - 1 <= 0) return false;
        // 上一篇
        $prev = DB::table('article')
            -> select('article_uuid', 'title')
            -> where('id', $id - 1)
            -> where('is_open', 1)
            -> first();
        if (!$prev) {
            $id = $id - 1;
            $this -> getPrevArt($id);
        } else {
            return $prev;
        }
    }
    /**
     * @name 配合editorMd上传图片
     *
     *
     * */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('editormd-image-file')) {
            if ($request->file('editormd-image-file')->isValid()) {
                $url = '/storage/app/'. $request -> file('editormd-image-file') -> store('editor');
                return [
                    'success'       => 1,
                    'message'       => '上传文件成功',
                    'url'           => asset($url)
                ];
            } else {
                return [
                    'success'       => 0,
                    'message'       => '上传文件失败',
                    'url'           => ''
                ];
            }
        } else {
            return [
                'success'       => 0,
                'message'       => '上传文件不能为空',
                'url'           => ''
            ];
        }
    }

    /**
     * @name 获取下一篇文章
     * @param $id int 文章id
     *
     * @return object|false
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date 2017-11-07
     **/
    private function getNextArt($id)
    {
        $next = DB::table('article')
            ->select('article_uuid', 'title')
            ->where('id', $id + 1)
            ->where('is_open', 1)
            ->first();
        if (!$next) return false;
        return $next;
    }
}

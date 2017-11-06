<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class CommentsController extends Controller
{
    public function addComments(Request $request)
    {
        $params = $request -> except('_token');
        $user = json_decode(session('home_user_id'));
        $params['user_uuid'] = $user -> uuid;
        $params['create_time'] = time();
        $id = DB::table('comments') -> insertGetId($params);
        if (!$id) return ['code' => 201, 'message' => '添加评论失败'];
        $user = json_decode(session('home_user_id'));
        return [
                'code' => 200,
                'data' => [
                    'contents'          => $params['contents'],
                    'create_time'       => formatDate($params['create_time']),
                    'id'                => $id,
                    'user_name'         => $user -> user_name,
                    'user_uuid'         => $user -> uuid,
                    'user_avatar'       => asset($user -> user_avatar)
                ]
        ];
    }
    
}

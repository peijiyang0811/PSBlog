<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* 裴纪阳 */

Route::get('/MyLove/MyQueen', function () {
    return view('mylove.queen');
});

/*-------------------------------------PS伐木累后台管理系统--------------------------------------*/
// 登陆
Route::get('/admin/login', 'Admin\LoginController@index');
Route::post('/admin/api/getavatar', 'Admin\LoginController@getAvatar');
Route::post('/admin/api/check', 'Admin\LoginController@checkPass');

Route::group(['prefix' => 'api'], function (){
    Route::post('getAdminLinks', 'LinksController@getLinks');
    Route::post('getOneLinks', 'LinksController@getOneLinks');
    Route::post('login', 'Blog\LoginController@login');
    Route::post('register', 'Blog\LoginController@newAccount');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'admin'], function (){

    // 后台首页
    Route::get('index', 'IndexController@index');
    /*----------------------------------------后台Ajax请求--------------------------------------------*/
    Route::group(['prefix' => 'ajax'], function () {
        // 服务器状态查询
        Route::post('systus', 'IndexController@sysStatus');
        // 删除用户
        Route::post('del/{id}', 'AccountController@delete');
    });
    /*----------------------------------------用户管理-----------------------------------------------*/
    Route::group(['prefix' => 'user'], function (){
        Route::get('list', 'AccountController@userList');
        Route::get('add', 'AccountController@addView');
        Route::post('addPost', 'AccountController@addPost');
        Route::get('edit/{id}', 'AccountController@edit');
        Route::post('update', 'AccountController@update');
        Route::post('delete', 'AccountController@delete');

    });
    /*---------------------------------------菜单管理-----------------------------------------------*/
    Route::group(['prefix' => 'menu'], function (){
        // 后台导航
        Route::get('admin', 'MenuController@admin');
        Route::post('addAdmin', 'MenuController@addAdminMenu');
        Route::post('updateAdmin', 'MenuController@updateAdmin');
        Route::post('delAdmin', 'MenuController@deleteAdmin');
        Route::get('edit/{id}', 'MenuController@edit');
        Route::post('update', 'MenuController@update');

    });
    /*---------------------------------------文章分类管理-----------------------------------------------*/
    Route::group(['prefix' => 'cate'], function (){
        Route::get('list', 'ArticleCateController@getIndex');
        Route::get('add', 'ArticleCateController@getAdd');
        Route::get('list/edit/{id}', 'ArticleCateController@getEdit');
        Route::post('insert', 'ArticleCateController@postInsert');
        Route::post('update', 'ArticleCateController@getUpdate');
        Route::post('del', 'ArticleCateController@getDel');
    });
    // 退出登陆
    Route::get('out', 'LoginController@loginOut');
});


/*-------------------------------------------- 前台页面 --------------------------------------------*/
Route::get('/', 'Blog\IndexController@index');
Route::get('/login', 'Blog\LoginController@index');
Route::get('/register', 'Blog\LoginController@register');
Route::get('/out', 'Blog\LoginController@out');
Route::get('/forget', 'Blog\LoginController@forget');
Route::get('/register', 'Blog\LoginController@register');
Route::get('/article/{uuid}', 'Blog\ArticleController@detail');
Route::get('/search/{words}', 'Blog\ArticleController@searchArticle');
Route::group(['prefix'=>'linux', 'namespace'=>'Blog'], function(){
    /*-----------------------------------------Linux----------------------------------------------*/
    Route::get('', 'ArticleController@linuxIndex');
});
Route::group(['namespace' => 'Blog', 'prefix'=>'center','middleware'=>'blog'], function (){
    // 个人资料
    Route::group(['prefix' => 'person'], function (){
        Route::get('', 'UserController@center');
    });
    // 博客
    Route::group(['prefix' => 'blog'], function (){
        Route::get('', 'UserController@article');
        Route::get('add', 'UserController@addArticle');
        Route::get('edit/{article_uuid}', 'UserController@edit');
        Route::post('add_blog', 'ArticleController@addBlog');
        Route::post('edit_blog', 'ArticleController@editBlog');
        Route::post('upload/image', 'ArticleController@uploadImage');
    });
    // 收藏
    Route::group(['prefix' => 'collect'], function (){
        Route::get('', 'UserController@collect');
    });
    // 消息
    Route::group(['prefix' => 'message'], function (){
        Route::get('', 'UserController@message');
    });
    Route::group(['prefix' => 'comments'], function (){
        Route::post('addComments', 'CommentsController@addComments');
    });

});

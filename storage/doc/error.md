# 遇到的问题

## 一.laravel 配置问题

### 1.文件权限问题
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;项目拉上去以后,需要重新设置属主,属组, `storage`,`bootstrap/cache` 的可写权限

    解决方案:

    更改属主属组及权限:
    `chmod -R 766 MyBlog` 递归给所有文件更改权限

    `chown -R www:www MyBlog` 递归给所有文件更改 属主数组


### 2.访问错误 `open_basedir`

```

Warning: require(): open_basedir restriction in effect. File(/home/wwwroot/MyBlog/bootstrap/autoload.php) is not within the allowed path(s): (/home/wwwroot/MyBlog/public/:/tmp/:/proc/) in /home/wwwroot/MyBlog/public/index.php on line 23

Warning: require(/home/wwwroot/MyBlog/bootstrap/autoload.php): failed to open stream: Operation not permitted in /home/wwwroot/MyBlog/public/index.php on line 23

Fatal error: require(): Failed opening required '/home/wwwroot/MyBlog/public/../bootstrap/autoload.php' (include_path='.:/usr/local/php/lib/php') in /home/wwwroot/MyBlog/public/index.php on line 23
```
> 描述: open_basedir限制了PHP能操作的目录,如果不在允许的范围内，php就不能访问。

> 解决方式:

> 通过命令 `grep -rn open_basedir /usr/local/nginx/`  查看在那个文件中 存在 open_basedir 字符

> 1.在项目根目录创建 `.user.ini` 文件,写入 open_basedir=/home/wwwroot/MyBlog:/tmp/:/proc/ 试验结果,不如意;

    `-rw-r--r--  1 root root     46 Aug 25 14:35 .user.ini`
    `chattr +i file` 添加锁定权限 -i 减去锁定权限 chattr 比 chmod 修改权限 更高级
> 2.修改 `fastcgi.conf`&nbsp; &nbsp;&nbsp;`vim /usr/local/nginx/conf/fastcgi.conf` 将网站根目录添加上

> `fastcgi_param PHP_ADMIN_VALUE "open_basedir=$document_root/:/tmp/:/proc/:/home/wwwroot";`

> 这个方法,是粗暴的方法

### 3.laravel部署在nginx 出现 nginx 403 forbidden 错误的处理

    如果不是权限问题，也不是索引文件index 不存在的问题。那就是，laravel的主目录指定错了。原来不能指定laravel程序的根目录。要指定在public目录。
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

# 开发中的错误

## 1.popen() has been disabled for security reasons
> 提示 `popen` 函数被禁用,这是因为服务器为了安全,php.ini 中设置了禁用函数列表
> 修改方法:vim php.ini,搜索 `disable_functions` ,将 popen 删除,保存,重启PHP

## 2.$.ajax post 报错:csrf 验证失败
```html
    1.在头部添加: <meta name="_token" content="{{ csrf_token() }}"/>

    2.$.ajax 设置:
            //  ajax 的post验证  需要加上  meta 头部  和 ajaxSetup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
```

## 3. post 表单 TokenMismatchException in VerifyCsrfToken.php line 68
 ```html
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
 ```

## 4.NotFoundHttpException in RouteCollection.php line 161:

> 描述:访问图片, http://test.blog.psfmaily.cn/storage/upload/avatar/default.png 报错

```
    1.第一种方案: 将图片存储在  唯一入口 public 下,创建相关目录结构
    ln -s /home/wwwroot/blog.psfmaily.cn/MyBlog/storage/ storage
    将 /public/storage  /storage/upload  添加到 .gitignore 里面
    2.第二种方案:laravel建议 将 上传资源存放在  storage/ 下面
        1 public function getImg($imgName){
            $path=storage_path().'/app/id/eInTskaZjE.jpg';    //获取图片位置的方法
            return response()->file($path);
        }
        2 Route::get('realname/get/{imgName}','RealnameController@getImg')->name('getimg');
        3 public function index()
            {
                $data=Personal::paginate(20);
                return view('admin.realname.index',compact('data'));
            }
        4 <td><img src="{{route('getimg', $v->img)}}" alt=""></td>
       经试验失败
```

## 5.Unable to guess the mime type as no guessers are available (Did you enable the php_fileinfo extension?)
> 主要原因是php_fileinfo未被开启.

解决#

找到php.ini

$  php -i | grep php.ini
Configuration File (php.ini) Path => /usr/local/php/etc
Loaded Configuration File => /usr/local/php/etc/php.ini
开启php_fileinfo

$ vim /usr/local/php/etc/php.ini
找到：;extension=php_fileinfo.dll去掉分号， windows是dll但如果是linux系统的话应该是 extension=php_fileinfo.so

> 若没有 fileinfo.so 文件,则自己编译安装

1.find / -name fileinfo
```
    第一步:
    # find / -name fileinfo
    若没有,则(注意,要和自己的PHP版本对应)
    wget -O php-7.0.19.tar.gz http://cn2.php.net/get/php-7.0.19.tar.gz/from/this/mirror && tar -zxvf php-7.0.19.tar.gz
    第二步:
    cd /root/soft/php-7.0.19/ext/fileinfo
    第三步:
    phpize
    第四步:
    ./configure -with-php-config=/usr/local/php/bin/php-config --enable-fileinfo
    第五步:
    [root@iZ8vb8kca7twx6158ame17Z fileinfo]# make && make install
    ----------------------------------------------------------------------
    Libraries have been installed in:
       /root/soft/php-7.0.19/ext/fileinfo/modules

    If you ever happen to want to link against installed libraries
    in a given directory, LIBDIR, you must either use libtool, and
    specify the full pathname of the library, or use the `-LLIBDIR'
    flag during linking and do at least one of the following:
       - add LIBDIR to the `LD_LIBRARY_PATH' environment variable
         during execution
       - add LIBDIR to the `LD_RUN_PATH' environment variable
         during linking
       - use the `-Wl,--rpath -Wl,LIBDIR' linker flag
       - have your system administrator add LIBDIR to `/etc/ld.so.conf'

    See any operating system documentation about shared libraries for
    more information, such as the ld(1) and ld.so(8) manual pages.
    ----------------------------------------------------------------------

    Build complete.
    Don't forget to run 'make test'.

    Installing shared extensions:     /usr/local/php/lib/php/extensions/no-debug-non-zts-20151012/
    第六步:
    确定 fileinfo.so 是否存在:
    ls /usr/local/php/lib/php/extensions/no-debug-non-zts-20151012
    第七步:
    vim /usr/local/php/etc/php.ini
    extension=fileinfo.so


    修改配置错误:
        Starting php-fpm [22-Sep-2017 14:21:41] NOTICE: PHP message: PHP Warning:  PHP Startup: Unable to load dynamic library '/usr/local/php/lib/php/extensions/no-debug-non-zts-20151012/fileinfo.so' - /usr/local/php/lib/php/extensions/no-debug-non-zts-20151012/fileinfo.so: undefined symbol: zval_used_for_init in Unknown on line 0

```

## 5.fopen(/home/wwwroot/blog.psfmaily.cn/MyBlog/storage/app/images/avatar/j0uqkmbAewm4U0Onsk2xYtu6pIey88VDweJwkF2w.png): failed to open stream: Permission denied

> 权限不够; chmod -R 775 images  chown -R www:www images

## 6.定时任务
    Laravel 定时任务

     分享 ⋅ rayjun ⋅ 于 2年前 ⋅ 最后回复由 wpby 于 3个月前 ⋅ 9426 阅读
    在 php 中使用定时器是一件不太简单的事情，之前大概只能通过 cron 来实现定时任务。但是在 Laravel5 中，定时任务将会变得很简单。

    Laravel Schedule#

    这个是 Laravel5 中新增加的特性之一。在 Laravel5 中，进入到 app/Console/Kernel.php 中，可以看到以下代码：

         protected function schedule(Schedule $schedule)
        {
            $schedule->command('inspire')
                     ->hourly();
        }
    这个 schedule 方法就是定时任务执行的关键，我们可以将所有的定时任务都放到其中，其中， Laravel 提供了诸多的方法来控制任务执行的时间间隔，例如：

        $schedule->command('foo')->everyFiveMinutes();

        $schedule->command('foo')->everyTenMinutes();

        $schedule->command('foo')->everyThirtyMinutes();

        $schedule->command('foo')->mondays();

        $schedule->command('foo')->tuesdays();

        $schedule->command('foo')->wednesdays();

        $schedule->command('foo')->thursdays();

        $schedule->command('foo')->fridays();

        $schedule->command('foo')->saturdays();

        $schedule->command('foo')->sundays();
    我们既可以通过创建 Command 来作为任务来执行，也可以使用闭包函数来作为任务：

        $schedule->call(function()
        {
                //TODO ...

        })->hourly();
    就这样，要执行的任务就可以简单的创建。

    启动 Schedule#

    在定义完以上的任务之后，可以通过 php artisan schedule:run 来执行这些任务，但是，这个任务执行一起，需要不断的执行这个这个命令定时器才能不断的运行，所以就需要 linux 的系统功能的帮助，在命令行下执行下面的命令：

        crontab -e
    执行完以上的命令之后，会出现一个处于编辑状态的文件，在文件中填入以下内容：

        * * * * * php /path/to/artisan schedule:run
    然后保存，关闭。上面命令的含义是每隔一分中就执行一下 schedule:run命令。这样一来，前面定义的任务就可以不断的按照定义的时间间隔不断的执行，定时任务的功能也就实现了。

    注：这个仅仅是在 linux 平台上，windows 还没研究过实现方法。

## 7.js 循环出来的 导航,不能赋值active属性;不能点击

##### 不能赋值 active 属性
    ```
        ajax 设置成同步
        $(function(){
                /*$.post('/api/getAdminLinks', {type:2}, function(json){
                    if (json.code == 200) {
                        var url = window.location.protocol +'//'+window.location.host;
                        var html = '<li class="sidebar-nav-heading">菜单 <span class="sidebar-nav-heading-info">Menu</span></li>';
                        $.each(json.data, function(k, v){
                            if (v.url == 'javascript:;') {
                                html += '<li class="sidebar-nav-link"><a href="javascript:;" class="sidebar-nav-sub-title"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'<span class="jiantou am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span></a>';
                                if (v.child) {
                                    html += '<ul class="sidebar-nav sidebar-nav-sub">';
                                    $.each(v.child, function(kk, vv){
                                        html += '<li class="sidebar-nav-link menu_li"><a href="'+url+vv.url+'" class="menu_a" level="2"><span class="'+vv.icon+' sidebar-nav-link-logo"></span> '+vv.title+'</a></li>';
                                    });
                                    html += '</ul>';
                                }
                                html += '</li>';
                            } else {
                                html += '<li class="sidebar-nav-link menu_li"><a href="'+url+v.url+'" class="menu_a"  level="1"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'</a></li>';
                            }
                        });
                        $('.sidebar-nav').html(html);
                    }
                });*/
                $.ajax({
                    url:'/api/getAdminLinks',
                    type:"POST",
                    dataType:"json",
                    data:{type:2},
                    success:function(json){
                        if (json.code == 200) {
                            var url = window.location.protocol +'//'+window.location.host;
                            var html = '<li class="sidebar-nav-heading">菜单 <span class="sidebar-nav-heading-info">Menu</span></li>';
                            $.each(json.data, function(k, v){
                                if (v.url == 'javascript:;') {
                                    html += '<li class="sidebar-nav-link"><a href="javascript:;" class="sidebar-nav-sub-title"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'<span class="jiantou am-icon-chevron-down am-fr am-margin-right-sm sidebar-nav-sub-ico"></span></a>';
                                    if (v.child) {
                                        html += '<ul class="sidebar-nav sidebar-nav-sub">';
                                        $.each(v.child, function(kk, vv){
                                            html += '<li class="sidebar-nav-link menu_li"><a href="'+url+vv.url+'" class="menu_a" level="2"><span class="'+vv.icon+' sidebar-nav-link-logo"></span> '+vv.title+'</a></li>';
                                        });
                                        html += '</ul>';
                                    }
                                    html += '</li>';
                                } else {
                                    html += '<li class="sidebar-nav-link menu_li"><a href="'+url+v.url+'" class="menu_a"  level="1"><i class="'+v.icon+' sidebar-nav-link-logo"></i> '+v.title+'</a></li>';
                                }
                            });
                            $('.sidebar-nav').html(html);
                        }
                    },
                    async: false// 设置同步方法
                });
                var curr_url = document.location.href;
                console.log(curr_url);
                $('.menu_a').each(function(){
                    var this_a = $(this);
                    // 获取当前链接的url
                    var this_url = this_a.attr('href');
                    // 判断当前 href 是不是 当前链接
                    if (curr_url.indexOf(this_url) != -1) {
                        // 是当前url 所在的链接
                        // 获取当前level 是一级链接还是 二级链接,其他的不用管
                        var level = this_a.attr('level');
                        switch (parseInt(level)) {
                            case 1:
                                // 是一级链接 增加 active样式
                                this_a.addClass('active');
                                break;
                            case 2:
                                // 是二级链接,对其自身元素和父级和爷爷级别的元素做样式添加
                                this_a.addClass('sub-active');
                                // 找他的爷爷元素 ul,让这个列表显示出来
                                this_a.parent().parent().css('display', 'block');
                                // 找他太太爷爷的第一个孩子的第二个孩子
                                this_a.parent().parent().parent().find('.jiantou').addClass('sidebar-nav-sub-ico-rotate');
                                this_a.parent().parent().parent().find('>a').addClass('active');
                                break;
                            default:
                                // 默认给一级链接样式
                                this_a.addClass('active');
                        }
                    }
                });
            });
    ```
##### 不能点击
> 原因,由于 导航链接是 js 循环输出的,所以原来 app.js 里面的点击事件是失效的,因为 它获取不了链接

```
    js 循环输出的内容,只能用  $(document).on('click', '.sidebar-nav-sub-title', function() {});
    设置单击事件
    // 侧边二级菜单
        $(document).on('click', '.sidebar-nav-sub-title', function() {
            $(this).siblings('.sidebar-nav-sub').slideToggle(80)
                .end()
                .find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
        });
```

## Markdown 继承
> 参照 [SimpleMDE编辑器 + 提取HTML + 美化输出](https://segmentfault.com/a/1190000009469890)

> 参照 [simplemde 使用+php获取html](http://m.blog.csdn.net/qq_28271035/article/details/77773759)


## [excel 导出] (https://github.com/Maatwebsite/Laravel-Excel)
 参考文章:http://www.bcty365.com/content-153-5559-1.html


## [微信开发](https://easywechat.org/)

##
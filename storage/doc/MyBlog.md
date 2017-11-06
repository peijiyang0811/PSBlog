# MyBlog需求分析 #
`2017-08-23`  `裴纪阳`
## 码云地址
> 私有仓库：https://git.oschina.net/peijiyang/MyBlog.git
## 框架选择
> Laravel 5.4 + vue.js

## 开发环境
>   1. windows 7 x64 旗舰版
>   2. PHP 7.0.10
>   3. MySQL 5.7.14
>   4. Apache 2.4.23

## 生产环境

>   1. CentOS release 6.9 (Final)
>   2. PHP 7.0.19
>   3. MySQL 5.5.56
>   4. Nginx 2.4.23

## 密码加密方法
```php
    strtoupper(strrev(md5(sha1(md5($password)) . $key)));
```
```javascript
    var key = '';
    var pwd = $.md5($.sha1($.md5(pwd))+key);
    js 传到后端以后,在进行翻转,转大写
```
## 加密函数
```php
    /**
     * @name 密码加密
     * @param string $password 待加密的字符串
     * @param string $key 加密密文
     * @param integer $type 1 PHP加密 2 JS加密后的字符串
     * @return string $encrypt
     *
     * @author peijiyang
     * @date 2017-08-25
     * */
    function passwordEncrypt(string $password, int $type, string $key = '') : string {
        if (empty($password)) return false;
        $key = empty($key) ? config('app.key') : $key;
        $encrypt = '';
        switch (intval($type)) {
            case 1:
                $encrypt = strtoupper(strrev(md5(sha1(md5($password)) . $key)));
                break;
            case 2:
                $encrypt = strtoupper(strrev($password));
                break;
            default:
                $encrypt = strtoupper(strrev(md5(sha1(md5($password)) . $key)));
                break;
        }
        return $encrypt;
    }
```
## 功能需求

## 用户标识记录

```
    > 1.后台使用 account 表的id字段; 前台使用 account 表的uuid字段

    > 2.使用redis记录用户标识,时效 10分钟 10*60=600 秒

    > 3.有任何操作的时候即更新这个 标识;无操作时,则不更新此记录
```

## 唯一标示使用

> 已安装 `Ramsey\Uuid` 类库

```
    使用方法:
    通过 composer 安装的第三方类库,都可以这么引用

    1.在控制器中直接 use Ramsey\Uuid\Uuid;引入类

    2.利用 laravel 的注册机制,在 app.php 的 `aliases` 数组中添加 一个值
        'Uuid'  => Ramsey\Uuid\Uuid::class,
```
> 生成唯一标示 Uuid
`Uuid::uuid4() -> toString();`

> 生成随机4位字符串
`Uuid::uuid4() -> getTimeMidHex();`

## 自定义业务逻辑层

命名,统一为  `xxService.php`;若是写的接口类,则 命名为 `xxxInterface.php`

统一在 `app/Service` 目录下面写;不同的功能,通过不同的 文件夹进行管理

每个类 根据规范,需要些命名空间.如 `namespace APP\Service;` `namespace\Service\MyService;`

使用时, `use App\Service\UserService.php`

## 数据模型层

数据操作统一放在 `app/Http/Models` 目录下,命名规范 类同 业务逻辑层

### 后台管理系统

#### 一、用户管理

##### 1、基本用户管理（增删查改）


#### 二、博文管理

#### 三、博客站点管理

##### 1、基本配置

> Redis 存储

##### 2、数据库备份

##### 3、轮播图管理

##### 4、友情链接管理

##### 5、敏感词设置

> redis 存储

##### 6、导航栏设置

#### 四、黑名单列表

#### 五、博文管理

#### 六、第三方登陆配置[暂不做]

#### 七、评论管理

### 博客网站功能需求

#### 一、首页

##### 1、导航栏

##### 2、面包屑（网站地图）

##### 3、推荐文章列表

##### 4、友情链接

##### 5、底部版权信息

#### 二、小功能

##### 1、工具箱

```php
    > 1.json 格式化输出
    
    > 2.日期|时间戳转换
    
    > 3.MD5加密|HASH加密|CRYPT加密解密

    > 4.分享博文

    > 5.markdown 编辑器 + 导出为PDF功能
```

##### 2、博文点赞|收藏|转发|阅读次数统计

##### 3、积分|经验系统


#### 三、评论功能

#### 四、控制台

##### 1、博文管理

##### 2、消息中心

##### 3、好友系统

##### 4、经验日志
```
    > 如何获取经验
        1.登陆给10经验
        2.发博文给我20经验
        3.博文被评论一次加1经验;被转发|分享一次加10经验
        4.激活邮箱加30经验
    > 经验用来干嘛?
        1.暂未知,分享的下载资源免费拿走
```

#### 五、个人中心

##### 1、基本资料的管理
```
1).头像修改
2).昵称修改
3).QQ\邮箱修改
4).地址修改(暂不需要)
``` 
##### 2.定值个人主页，用于交朋友展示（暂搁置，属于博客皮肤选择功能）
```php
    > 1.个人主页背景更换，自定义背景图（限制图片大小）
    > 2.布局选择
    > 3.预计略复杂，先不做
```

## 数据库设计

[数据库名称:  ***psblog***]

***用户表 `ps_account`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_name||varchar|30|X||用户昵称|
|real_name||varchar|30||''|真实姓名,管理员用|
|uuid||char|36|X|''|用户唯一标示|
|rule_id||varchar|5|X|1|用户组id 1=>博客用户 2=>后台管理员,多个权限使用 , 分割 1,20|
|user_password||char|32|X||用户密码|
|admin_password||char|32|X||管理员密码(默认与普通密码一样)|
|user_avatar||varchar|500||'/storage/app/images/avatar/default.png'|用户头像|
|user_phone||char|11||''|联系方式|
|qq_openid||varchar|50||''|QQ openid|
|wechat_openid||varchar|50||''|微信 openid|
|sina_openid||varchar|50||''|新浪 openid|
|visit_count||int|10|X|0|个人主页访问次数|
|status||tinyint|2|X|1|用户状态:1=>正常 2=>禁言 10=>封禁|
|experience||int|10|X|0|经验值|
|request_ip||int|10|X|0|最近一次登陆的ip|
|request_port||int|8|X|0|最近一次登陆的端口|
|request_area||varchar|300|||最近一次登陆的地址位置|
|login_count||int|10|X|0|登陆次数累加|
|last_login_time||int|11|||最近一次登陆时间|
|device||tinyint|1|||登录设备 1=>PC 2=>Android 3=>Iphone 4=>未知|
|device_system||varchar|255|||最近一次的登陆的操作系统|
|create_time||int|11|||注册时间|
|update_time||int|11|||修改时间|

***登陆日志表 `ps_loginLog`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_uuid||int|10|X||与account表的id关联|
|login_type||tinyint|1|X|1|登陆来源 1=>前台 2=>后台管理|
|user_agent||varchar|255|X|''|用户浏览器标识|
|request_ip||int|10|X|0|最近一次登陆的ip|
|request_port||int|8|X|0|最近一次登陆的端口|
|request_area||varchar|300|||最近一次登陆的地址位置|
|device||tinyint|1|||登录设备 1=>PC 2=>Android 3=>Iphone 4=>未知|
|device_system||varchar|255|||登陆的操作系统|
|create_time||int|11|X||创建时间|


***经验日志表 `ps_experienceLog`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_uuid||int|10|X||与account表的id关联|
|describe||varchar|100|X|''|经验描述|
|score||tinyint|4|||经验值|
|create_time||int|11|X||创建时间|

***文章分类表 `ps_articleCategory`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|title||varchar|50|X|''|分类名称|
|pid||int|10|X|0|父级id|
|path||varchar|100|||路径|
|status||tinyint|1|X|1|状态 1=>启用 2=>关闭|
|admin_id||int|10||0|管理员id|
|update_time||int|11|||修改时间|
|create_time||int|11|||添加时间|

***标签表 `ps_articleTag`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|title||varchar|50|X|''|标签名称|
|user_uuid||char|36|X||添加人|
|status||tinyint|1|X|1|状态 1=>启用 2=>关闭|
|admin_id||int|10||0|管理员id|
|admin_time||int|11|||管理员处理时间|
|create_time||int|11|||添加时间|
|update_time||int|11|||修改时间|

***文章表 `ps_article`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_uuid||char|36|X||文章属主|
|article_uuid||char|36|X||文章唯一标示|
|tag_ids||varchar|30|X||文章分类id集合 1,2,10|
|cate_id||int|10|X||文章分类id|
|title||varchar|100|X||文章标题|
|subtitle||varchar|200|||副标题|
|contents||longtext||X||详细内容|
|visit_count||int|10|X|0|查看次数|
|vote_count||int|10|X|0|点赞数量|
|collect_count||int|10|X|0|收藏的数量|
|status||tinyint|1|X|4|文章状态 1=>审核失败 2=>删除 3=>关闭 4=>审核成功|
|recommend||tinyint|1|X|1|是否推荐 1=>不推荐 2=>推荐|
|admin_id||tinyint|2|X|0|审核人id|
|check_time||int|11|X|0|审核时间|
|check_message||varchar|500||''|审核描述|
|create_time||int|11|X||发布时间|
|update_time||int|11|||更新时间|

***文章点赞表 `ps_articleVote`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|article_uuid||char|36|X||文章表的唯一标示|
|user_uuid||char|36|X||收藏人的唯一标示|
|status||tinyint|1|X|1|状态 1=>点赞 2=>取消点赞|
|create_time||int|11|X||创建时间|
|update_time||int|11|X||更新时间|

***文章收藏表 `ps_articleCollect`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|article_uuid||char|36|X||文章表的唯一标示|
|user_uuid||char|36|X||收藏人的唯一标示|
|status||tinyint|1|X|1|状态 1=>收藏 2=>取消收藏|
|create_time||int|11|X||创建时间|
|update_time||int|11|X||更新时间|


***导航栏表 `ps_navigate`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|title||varchar|50||''|导航名称|
|admin_id||int|10|X||添加人|
|status||tinyint|1|X|1|状态 1=>启用 2=>关闭|
|url||varchar|50|X||链接 /admin/account/updateAll|
|type||tinyint|1|X|1|类型 1=>前台 2=>后台|
|create_time||int|11|X||添加时间|
|update_time||int|11|||修改时间|

***友情链接表 `ps_links`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|title||varchar|50|X|''|导航名称|
|admin_id||int|10|X||添加人|
|status||tinyint|1|X|1|状态 1=>启用 2=>关闭|
|site_url||varchar|50|X||链接 https://blog.baijunyao.com|
|site_logo||varchar|255|X||网站logo图片的链接|
|contact_name||varchar|30||''|联系人|
|contact_phone||char|11|||联系电话|
|contact_qq||varchar|15|||联系QQ|
|create_time||int|11|||添加时间|
|update_time||int|11|||修改时间|

***评论表 `ps_comments`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_uuid||char|36|X||用户标识|
|article_uuid||char|36|X||文章标识|
|contents||varchar|255|X||评论内容|
|parent_id||int|10||0|父级id|
|status||tinyint|1|X|1|状态 1=>正常 2=>关闭|
|admin_id||int|10|0|0|管理员id|
|create_time||int|11|||添加时间|
|update_time||int|11|||修改时间|

***轮播图表 `ps_slide`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|title||varchar|50|X|''|轮播图名称|
|admin_id||int|10|X||添加人|
|slide_image||varchar|255|X||轮播图图片|
|slide_url||varchar|255||''|轮播图链接|
|status||tinyint|1|X|1|状态 1=>启用 2=>关闭|
|create_time||int|11|||添加时间|
|update_time||int|11|||修改时间|

***系统黑名单表 `ps_blacklist`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|10|X||自增主键|
|user_uuid||char|36|X||被拉黑人的uuid|
|message||varchar|255|X||拉黑原因行为描述|
|status||tinyint|1|X|1|状态 1=>拉黑 2=>取消拉黑|
|admin_id||int|10|X||管理员id|
|admin_time||int|11|X||管理员操作/更新时间|
|create_time||int|11|X||创建时间|

***地区表 `ps_area`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|mediumint|6|X||自增主键|
|name||varchar|10|X||城市名称|
|code||char|6|||地区编码|
|level||char|1|X|1|等级，1为省，2为市，3为县或区|
|pid||mediumint|8||0|父级id|
|initial||char|1|||首字母|

***管理员操作记录表 `ps_adminLog`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|11|X||自增主键|
|admin_id||int|10|X||操作人的id|
|url||varchar|255|X||操作的动作|
|describe||varchar|100|X||操作描述|
|request_ip||int|11|X||操作的ip|
|request_port||int|8|X||操作的端口|
|request_area||varchar|255|X||操作的地理位置|
|user_agent||varchar|300|X||浏览器版本|
|create_time||int|11|X||操作时间|

***后台菜单表 `ps_adminMenu`***

|字段|主键|类型|长度|允许为空|默认值|描述|
|:---|:---:|:---:|:---:|:---:|:---|:---|
|id|√|int|3|X||自增主键|
|title||varchar|30|X||名称|
|url||varchar|50|X||地址: /admin/user/manage|
|icon||varchar|50|X|'am-icon-desktop'|链接的小图标,默认值:|
|status||tinyint|1|X|1|状态1=>启用 2=>关闭|
|admin_id||int|10|X||添加人|
|create_time||int|11|X||添加时间|
|edit_admin_id||int|10|X||修改人的id|
|update_time||int|11|X||修改时间|

***表结构***

``详见 /storage/sql/psblog_data.sql 文件``
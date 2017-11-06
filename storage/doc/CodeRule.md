# 编码规范

`author:裴纪阳`

`date:2017-08-24`

### 一.命名
```
    > 1.[函数|方法名|类名] 全部使用 驼峰命名法;命名尽量用约定俗成的名称
        eg: userName

    > 2.常量 统一大写字母命名 eg:DB_NAME

    > 3.类中的 [protected|private] 方法名及变量名,统一以 下划线 _ 开头
        eg:protected $_appId = 'dsaf45678';

    > 4.不能用单字母作为变量名,如 i n m ,循环结构中用于迭代的除外for($i = 1; $i <= 10; $i++)

    > 5.字符串拼接不要使用双引号,无特殊情况,都是用单引号
```
### 二.编码

> 使用无BOM的UTF-8
> 所有PHP文件,在 namespace 声明完成后,都需加上 `header('Content-type:text/html; charset=utf-8');`

```php
    <?php
    namespace App\Controller;
    // 空出一格
    header('Content-type:text/html; charset=utf-8');
```
> 缩进:统一使用 缩进4格

### 三.书写规范

#### (1).文件

> 1.纯PHP代码文件必须省略最后的闭合标签

> 2.所有PHP代码文件必须以一个空行结束

#### (2).注释示例

```php
    /**
     * @name 功能描述
     * @param string $string   描述
     *        参数类型 参数名称 参数描述
     * @return array $array
     *         返回值类型 返回值参数
     *
     * @author peijiyang<peijiyang@163.com>
     * @date 2017-08-24
     */
```

#### (3).代码示例

##### A.变量声明

```php
    // true false null 必须小写
    $address = '河南省郑州市';
    static $employee = '丛支超';
    global $leader = '裴纪阳';
    $boolean = true;
    class User
    {
        public $name;
        protected $_firstName;
        private $_lastName;
    }
    // 数组声明

    $array = [12, 456, 78];

    $array = [
        ['firstName' => '丛', 'lastName' => '支超'],
        ['firstName' => '杜', 'lastName' => '瑞刚'],
        ['firstName' => '裴', 'lastName' => '纪阳']
    ];

    // 常量定义
    const SITE_URL = 'http://blog.psfmaily.cn';
```

##### B.函数声明,采用PHP7的新语法

```php
    // PHP7 增加了4中标量类型的参数约束;返回值类型约束
    function returnString(string $string, int $age, array $array, boolean $boolean) : string {
        return '4132';
    }
```

##### C.循环结构

```php
     for ($i = 0; $i <= 10; $i++) {
        /*代码块*/
     }

     foreach ($array as $key => $value) {
        /*代码块*/
     }

     while ($param) {
        /*代码块*/
     }

```

##### D.try...catch

```php
    try {

    } catch (Exception $e) {

    } catch (Exception $e) {

    }
```

##### E. if..else..else if

```
    if () {

    } else if () {

    } else {

    }
```

##### F. switch

```
    /*
    |-------------------------------------------------------
    | switch 判断的条件,不管接收到的值是什么,都先转换一下,
    | eg : switch (intval($param)) 将变量转换为int类型
    |      switch (strval($param)) 将变量转换为 string 类型
    |-------------------------------------------------------
    */
    switch (intval($age)) {
        case 1:
            return 1;
            break;
        case 18:
            return 18;
            break;
        default:
            return 0;
            break;
    }
```
##### G.类声明

```
    class User [extends Base implements interface1, interface2,.......]
    {

    }
```

##### H.类方法可见性及方法修饰

```
    class User [extends Base implements interface1, interface2,.......]
    {
        /**
         * 每个方法都应该有一个注释
         *
         */
        public function index() {

        }
        private function _edit() {

        }
        protected function _update() {

        }
        /**
         * static 修饰符 应该在 可见性修饰符后面
         *
         */
        protected static function _update() {

        }
    }
```
##### I.类的属性|方法 使用

```
    $class -> name;
    $class -> sayHello();
```
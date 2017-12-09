<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 自定义验证规则
        // 手机号格式
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            $len = strlen($value);
            $phone = '';
            for ($i = 0;$i < $len; $i++) {
                if ($value[$i] != ' ') $phone .= $value[$i];
            }
            if (!is_numeric($phone)) {
                return false;
            }
            return preg_match('/^13[\d]{9}$|^14[4,7,9]{1}\d{8}$|^15[^4]{1}\d{8}$|^166\d{8}$|^17[^4|^9]{1}\d{8}$|^18[\d]{9}$|^19[8,9]{1}\d{8}$/', $phone) ? $phone : false;
        });
        // 汉字
        Validator::extend('mbstr', function ($attribute, $value, $parameters, $validator) {
            // js /^[\u4e00-\u9fa5]{2,10}$/
            // php utf8  /^[\x{4e00}-\x{9fa5}]{2,10}$/u
            // php gbk bg2312 '/^['.chr(0xa1).'-'.char(0xff)']$/'
            return preg_match('/^[\x{4e00}-\x{9fa5}]{2,10}$/u', $value) ? $value : false;
        });
        // URL 验证
        Validator::extend('is_url', function ($attribute, $value, $parameters, $validator) {
            // js /^[\u4e00-\u9fa5]{2,10}$/
            // php utf8  /^[\x{4e00}-\x{9fa5}]{2,10}$/u
            // php gbk bg2312 '/^['.chr(0xa1).'-'.char(0xff)']$/'
            return preg_match('/^(http|https|ftp)\:\/\/[a-zA-Z0-9]+([a-zA-Z0-9\-\.]+)?\.(com|org|net|mil|edu|COM|ORG|NET|MIL|EDU)$/', $value) ? true : false;
        });
        // QQ 验证
        Validator::extend('is_qq', function ($attribute, $value, $parameters, $validator) {

            return preg_match('/^[1-9][0-9]{4,}$/u', $value) ? true : false;
        });
        //sql调试

        /*DB::listen(function ($sql) {
            foreach ($sql->bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $sql->bindings[$i] = "'$binding'";
                    }
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
            $query = vsprintf($query, $sql->bindings);
            print_r($query);
            echo '<br />';
        });*/

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

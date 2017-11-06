<?php

namespace App\Service;
use App\Http\Models\Account;
class PasswordService
{
    private static $_key;      // 加密字符

    /**
     * @name 加密一个密码
     * @param string $password
     * @param string $key 加密秘钥
     * @param int $type 类型 1 PHP加密 2 js加密
     * @return string $cryptPassword
     *
     * @author peijiyang
     * @date 2017-08-28
     * */
    public static function make(string $password, int $type, string $key = '') : string {
        if (empty($password)) return false;
        self::$_key = empty($key) ? env('app.key') : $key;
        return passwordEncrypt($password, $type, $key);
    }

    /**
     * @name 检查密码是否正确
     * @param string $needPassword
     * @param string $user_name
     * @return boolean
     *
     * @author peijiyang
     * @date 2017-08-28
     * */
    public static function checkPassword(string $needPassword, string $user_name) : boolean {
        $account = Account::select('id')->where('user_password', $needPassword) -> first();
        if (!$account) return false;
        return true;
    }
}
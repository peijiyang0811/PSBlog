<?php
/**
 * Created by PhpStorm.
 * User: hhh
 * Date: 2017/8/28 0028
 * Time: 17:03
 */
namespace App\Http\Models;
class Account extends Base
{
    protected $table = 'account';
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_name',
        'user_password',
        'admin_password',
        'real_name',
        'user_phone',
        'user_email',
        'rule_id',
        'create_time',
        'uuid',
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: hhh
 * Date: 2017/8/28 0028
 * Time: 17:03
 */
namespace App\Http\Models;
class Links extends Base
{
    protected $table = 'links';
    public $timestamps = true;//是否应该被打上时间戳
    protected $dateFormat = 'U';// 指定存储时间列的格式，U 时间戳格式，默认是 yyyy-mm-dd Hh:mm:ss
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    /**
     * 访问修改器
     * @name 外部使用， $link -> status_name
     * @param $value
     * @return string
     *
     * @author peijiyang<peijiyang@psfmaily.cn>
     * @date
     **/
    public function getStatusNameAttribute($value)
    {
        switch (intval($value)) {
            case 1:
                return '<span class="am-badge am-badge-success am-round am-text-sm">正常</span>';
                break;
            case 2:
                return '<span class="am-badge am-badge-warning am-round am-text-sm">禁用</span>';
                break;
        }
    }

    /**
     * 定义一个修改器
     *
     * @param  string  $value
     * @return string
    $user = App\User::find(1);
    $user->first_name = 'Sally';此时会主动调用此函数
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }
   * */
}

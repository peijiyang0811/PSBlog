<?php
/**
 * 自定义数据模型基类
 * User: peijiyang
 * Date: 2017/8/28 0028
 * Time: 14:59
 */
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    public $timestamps = false;
    protected $_create_time;
    protected $_update_time;
    /**
     * protected $table;// 定义数据表
     * protected $primaryKey;// 自定义主键
     * public $timestamps = false;//是否应该被打上时间戳
     *  protected $dateFormat = 'U';//模型日期列的存储格式
     * protected $fillable = [];//可以被批量赋值的属性。设置好可以被批量赋值的属性，便能通过 create 方法来添加一条新记录到数据库。
     * create 方法将返回已经被保存的模型实例：
     *
     * protected $guarded = []//不可被批量赋值的属性
     *
     * */

    /**
     * @name 自动识别表名或者使用传入的表名
     * @param string $tableName
     *
     * @author peijiyang
     * @date 2017-08-28
     * */
    public function __construct()
    {
        parent::__construct();
        $this -> _create_time = time();
        $this -> _update_time = time();
    }
    /**
     * 使用作用域扩展 Builder 链式操作
     *
     * 示例:
     * $map = [
     *     'id' => ['in', [1,2,3]],
     *     'category_id' => ['<>', 9],
     *     'tag_id' => 10
     * ]
     *
     * @param $query
     * @param $map
     * @return mixed
     */
    public function scopeWhereMap($query, $map)
    {
        // 如果是空直接返回
        if (empty($map)) {
            return $query;
        }
        // 判断各种方法
        foreach ($map as $k => $v) {
            if (is_array($v)) {
                $sign = strtolower($v[0]);
                switch ($sign) {
                    case 'in':
                        $query->whereIn($k, $v[1]);
                        break;
                    case 'notin':
                        $query->whereNotIn($k, $v[1]);
                        break;
                    case 'between':
                        $query->whereBetween($k, $v[1]);
                        break;
                    case 'notbetween':
                        $query->whereNotBetween($k, $v[1]);
                        break;
                    case 'null':
                        $query->whereNull($k);
                        break;
                    case 'notnull':
                        $query->whereNotNull($k);
                        break;
                    case '=':
                    case '>':
                    case '<':
                    case '<>':
                        $query->where($k, $sign, $v[1]);
                        break;
                }
            } else {
                $query->where($k, $v);
            }
        }
        return $query;
    }
    /**
     * 删除数据
     *
     * @param  array $map   where 条件数组形式
     * @return bool         是否成功
     */
    public function deleteData($map)
    {
        //软删除
        $result=$this
            ->where($map)
            ->delete();
        if ($result) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 添加数据
     *
     * @param  array $data 需要添加的数据
     * @return bool        是否成功
     */
    public function addData($data)
    {

        //添加数据
        $data['create_time'] = $this -> _create_time;
        $result=$this
            ->create($data)
            ->id;
        if ($result) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 修改数据
     *
     * @param  array $map  where条件
     * @param  array $data 需要修改的数据
     * @return bool        是否成功
     */
    public function editData($map, $data)
    {
        $model = $this->whereMap($map)->first();
        // 可能有查不到数据的情况
        if ($model->isEmpty()) {
            return false;
        }
        foreach ($data as $k => $v) {
            $model->{$k} = $v;
        }
        $result = $model->save();
        if ($result) {
            return true;
        }else{
            return false;
        }
    }
}
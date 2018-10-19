<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/16
 * Time: 10:36
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden=['update_time','delete_time'];
    protected $autoWriteTimestamp=true;
    //数据库字段不是create_time时需要指定字段
    /*protected $createTime='createtime';
    protected $updateTime='updatetime';
    protected $deleteTime='deletetime';*/
    /**
     * 查询个人订单
     * */
    public static function getSnapOrder($uid,$page=1,$len=15){
        //订单按照时间倒序排列,返回的时paginate对象
        $pagdata = self::where('user_id','=',$uid)
           ->order('create_time desc')
           ->paginate($len,true,['page'=>$page]);
       return $pagdata;
    }
}
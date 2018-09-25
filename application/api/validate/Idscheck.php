<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/25
 * Time: 16:06
 */

namespace app\api\validate;


class Idscheck extends BaseValidate
{
    protected $rule=[
        'ids'=>'require|checkid'
    ];
    protected $message=[
        'ids'=>'ids必须是以逗号分隔的多个正整数'
    ];
    //将参数分解数组进行检测
    protected function checkid($value){
        $values=explode(',',$value);
        //数组为空
        if(empty($values)){
            return false;
        }
        foreach ($values as $id){
            //参数不是正整数
            if(!$this->Isidint($id)){
                return false;
            }
        }
        return true;
    }
}
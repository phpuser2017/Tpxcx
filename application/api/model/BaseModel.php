<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/20
 * Time: 16:34
 */

namespace app\api\model;


use think\Model;

class BaseModel extends Model
{
    //图片域名配置处理
    protected function urlfix($value,$data){
        $returnurl=$value;
        //判断图片类型，是否需要添加域名(from=1)
        if($data['from']==1){
            $returnurl=config('setting.img_pre').$value;
        }
        return $returnurl;
    }
}
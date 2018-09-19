<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 23:21
 */
namespace app\api\model;

use think\Model;

class Banner extends Model
{
    //定义关联
    public function banneritems(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
    //封装调用模型
    public static function getbannerid($id){
        $bannres=self::with(['banneritems','banneritems.img'])->find($id);
        return $bannres;
    }
    
}
<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 23:21
 */
namespace app\api\model;


class Banner extends BaseModel
{
    //隐藏不需要显示的字段
    protected $hidden=['delete_time','update_time'];
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
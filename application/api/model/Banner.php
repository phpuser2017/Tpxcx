<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 23:21
 */
namespace app\api\model;

use think\Db;
use think\Exception;

class Banner
{
    public static function getbannerid($id){
//        $banner=Db::query('select * from banner_item where banner_id=?',[$id]);
//        $banner=Db::table('banner_item')->where('banner_id','=',$id)->select();
        $banner=Db::table('banner_item')
            ->where('id','=',3)
		    ->select();
        return $banner;
        
//        return null;
    
    }
    
}
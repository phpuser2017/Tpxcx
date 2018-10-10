<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: fan
 * Date: 2018/09/25
 * Time: 14:55
=======
 * User: Fanji
 * Date: 2018/9/21
 * Time: 8:13
>>>>>>> 429fd28d766ff72f32d5cd8a476ddced02097709
 */

namespace app\api\model;


class Theme extends BaseModel
{
    //表之间进行关联
    public function Topicimg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
    public function Headimg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    //theme和product通过第三张表进行多对多关联
    public function products(){
        //belongsToMany('关联模型名','中间表名','外键名','当前模型关联键名',['模型别名定义']);
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }
    //封装主题图片关联查询
    public static function getthemes($ids){
        $theme=self::with(['Topicimg','Headimg'])->select($ids);
        return $theme;
    }
    //封装主题详情关联查询
    public static function getproduct($id){
        $product=self::with(['products','Topicimg','Headimg'])->find($id);
        return $product;
    }
    //隐藏不需要显示的字段
    protected $hidden=['delete_time','update_time','topic_img_id','head_img_id'];
}
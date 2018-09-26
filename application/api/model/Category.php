<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 10:47
 */

namespace app\api\model;


class Category extends BaseModel
{
    //隐藏不需要显示的字段
    protected $hidden=['delete_time','update_time','topic_img_id'];
    //关联图片：分类页顶部图片
    public function topicimg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
}
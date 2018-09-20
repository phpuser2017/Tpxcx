<?php

namespace app\api\model;


class BannerItem extends BaseModel
{
    //显示需要显示的字段
    protected $visible=['id','key_word','type','img'];
    //关联image
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}

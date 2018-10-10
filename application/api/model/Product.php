<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: fan
 * Date: 2018/09/25
 * Time: 14:56
=======
 * User: Fanji
 * Date: 2018/9/21
 * Time: 8:13
>>>>>>> 429fd28d766ff72f32d5cd8a476ddced02097709
 */

namespace app\api\model;


class Product extends BaseModel
{
    //隐藏不需要显示的字段
    protected $hidden=['create_time','update_time','delete_time','pivot','category_id'];
    public function getMainImgUrlAttr($value,$data){
        return $this->urlfix($value,$data);
    }
    public static function getnewproduct($count){
        $products=self::limit($count)->order('create_time desc')->select();
        return $products;
    }
    //查询分类下商品
    public static function getallproduct($id){
        $all=self::where('category_id','=',$id)->select();
        return $all;
    }
    //商品详情模型关联
    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }
    public function property(){
        return $this->hasMany('ProductProperty','product_id','id');
    }
    //查询商品详情
    public static function getproductdetail($id){
        //模型中使用闭包函数进行数据处理(对关联模型中的关联模型中的字段进行排序)
        $detail=self::with(['property'])
            ->with([
                'imgs'=>function($query){
                    $query->with(['detailimg'])->order('order','asc');
                }
            ])
            ->find($id);
        return $detail;
    }
}
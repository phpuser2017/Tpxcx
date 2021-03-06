<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/26
 * Time: 8:05
 */

namespace app\api\controller\v1;


use app\api\model\Product as ProductModel;
use app\api\validate\Idvaliadet;
use app\api\validate\Productnumcheck;
use app\exception\ProductException;

class Product
{
    //最新商品
    public function getnewproducts($count=12){
        (new Productnumcheck())->goCheck();
        $products = ProductModel::getnewproduct($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        //在此接口中隐藏部分字段不影响模型中的查询显示
//        $collection=collection($products);
        $products=$products->hidden(['summary','img_id']);
        return json($products);
    }
    //获取所有商品
    public function getallproduct($id){
        (new Idvaliadet())->goCheck();
        //查询商品
        $allproduct=ProductModel::getallproduct($id);
        if($allproduct->isEmpty()){
            throw new ProductException();
        }
        $allproduct=$allproduct->hidden(['summary','img_id']);
        return $allproduct;
    }
    //点击商品显示详情
    public function productdetail($id){
        (new Idvaliadet())->goCheck();
        $productdata=ProductModel::getproductdetail($id);
        if(!$productdata){
            throw new ProductException();
        }
        return $productdata;
    }
}
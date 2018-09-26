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
}
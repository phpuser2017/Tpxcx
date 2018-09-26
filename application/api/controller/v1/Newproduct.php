<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/26
 * Time: 8:05
 */

namespace app\api\controller\v1;


use app\api\model\Product;
use app\api\validate\Productnumcheck;
use app\exception\NewproductException;

class Newproduct
{
    public function getnewproducts($count=12){
        (new Productnumcheck())->goCheck();
        $products = Product::getnewproduct($count);
        if (!$products) {
            throw new NewproductException();
        }
        //在此接口中隐藏部分字段不影响模型中的查询显示
        $collection=collection($products);
        $products=$collection->hidden(['summary','img_id']);
        return json($products);
    }
}
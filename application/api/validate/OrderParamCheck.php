<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/12
 * Time: 16:39
 */

namespace app\api\validate;


use app\exception\ParamExcepotion;

class OrderParamCheck extends BaseValidate
{
    protected $rule=[
      'products'=>'CheckProducts'
    ];
    //对参数中商品id\数量验证
    protected $singerule=[
        'product_id'=>'require|Isidint',
        'productnum'=>'require|Isidint'
    ];
    //验证参数
    protected function CheckProducts($values){
        if(!is_array($values)){
            throw new ParamExcepotion([
                'mag'=>'订单参数不正确'
            ]);
        }
        if(empty($values)){
            throw new ParamExcepotion([
                'mag'=>'订单参数不能为空'
            ]);
        }
        //遍历订单参数数组对各数组中商品id、商品数量进行验证
        foreach ($values as $value){
            $this->CheckProduct($value);
        }
    }
    //验证商品id、商品数量
    protected function CheckProduct($value){
        $singevalidate=new BaseValidate($this->singerule);
        $reault=$singevalidate->check($value);
        if(!$reault){
            throw new ParamExcepotion([
                'msg'=>'订单参数中商品id或商品数量错误'
            ]);
        }
    }
}
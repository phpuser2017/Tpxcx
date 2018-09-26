<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/26
 * Time: 8:06
 */

namespace app\api\validate;


class Productnumcheck extends BaseValidate
{
    protected $rule=[
        'count'=>'Isidint|between:1,12'
    ];
    protected $message=[
        'count'=>'商品数量须为正整数且大于等于1小于等于12'
    ];
}
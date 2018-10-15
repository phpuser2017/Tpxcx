<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/15
 * Time: 14:21
 */

namespace app\exception;


class OrderException extends BaseException
{
    public $code=404;
    public $msg='订单不存在，请检查商品id';
    public $errorcode=80000;
}
<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/26
 * Time: 8:12
 */

namespace app\exception;


class ProductException extends BaseException
{
    public $code=400;
    public $msg='没有最新商品';
    public $errorcode=20000;
}
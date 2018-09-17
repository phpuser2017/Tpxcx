<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/16
 * Time: 18:08
 */

namespace app\exception;


use think\Exception;

class BaseException extends Exception
{
    public $code=400;
    public $msg='参数错误';
    public $errorcode=10000;
    
}
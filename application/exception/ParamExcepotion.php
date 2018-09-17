<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/17
 * Time: 11:21
 */

namespace app\exception;

class ParamExcepotion extends BaseException
{
    public $code=400;
    public $msg='Param参数错误';
    public $errorcode=10000;
    
}
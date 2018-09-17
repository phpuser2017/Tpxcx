<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/16
 * Time: 18:10
 */

namespace app\exception;

class BannerException extends BaseException
{
    public $code=400;
    public $msg='banner不存在';
    public $errorcode=40000;
}
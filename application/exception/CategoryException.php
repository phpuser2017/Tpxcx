<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 11:15
 */

namespace app\exception;


class CategoryException extends BaseException
{
    public $code=400;
    public $message='分类不存在';
    public $errorcode=50000;
}
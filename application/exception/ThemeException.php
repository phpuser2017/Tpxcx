<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/25
 * Time: 17:33
 */

namespace app\exception;


class ThemeException extends BaseException
{
    public $code=400;
    public $msg='theme主题不存在';
    public $errorcode=30000;
}
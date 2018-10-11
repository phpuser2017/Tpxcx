<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/11
 * Time: 14:25
 */

namespace app\exception;


class UserException extends BaseException
{
    public $code=404;
    public $msg='用户不存在';
    public $errorcode=60000;
}
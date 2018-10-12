<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/12
 * Time: 11:49
 */

namespace app\exception;


class ForbiddenException extends BaseException
{
    public $code=403;
    public $msg='权限不够，禁止访问';
    public $errorcode=10001;
}
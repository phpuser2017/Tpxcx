<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/9
 * Time: 8:10
 */

namespace app\exception;


class TokenException extends BaseException
{
    public $code=401;
    public $msg='Token已过期或Token无效';
    public $errorcode=10001;
}
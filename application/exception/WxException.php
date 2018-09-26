<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 17:07
 */

namespace app\exception;


class WxException extends BaseException
{
    public $code=400;
    public $msg='微信api调用失败';
    public $errorcode=999;
}
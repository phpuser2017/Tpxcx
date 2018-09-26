<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:00
 */

namespace app\api\validate;


class CodeCheck extends BaseValidate
{
    protected $rule=[
        'code'=>'require|Isnotempty'
    ];
    protected $message=[
        'code'=>'code不能为空'
    ];
}
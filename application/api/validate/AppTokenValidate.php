<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 14:01
 */

namespace app\api\validate;


class AppTokenValidate extends BaseValidate
{
    protected $rule=[
        'ac'=>'require|Isnotempty',
        'se'=>'require|Isnotempty'
    ];
}
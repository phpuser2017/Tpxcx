<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/11
 * Time: 11:28
 */

namespace app\api\validate;

class AddressEdit extends BaseValidate
{
    protected $rule=[
        'name'=>'require|Isnotempty',
//        'mobile'=>'require|IsMobile',
        'province'=>'require|Isnotempty',
        'city'=>'require|Isnotempty',
        'country'=>'require|Isnotempty',
        'detail'=>'require|Isnotempty'
    ];
}
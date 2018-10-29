<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/19
 * Time: 15:58
 */

namespace app\api\validate;


class PageParamValidate extends BaseValidate
{
    protected $rule=[
        'page'=>'Isidint',
        'len'=>'Isidint'
    ];
    protected $message=[
        'page'=>'分页参数必须为正整数',
        'len'=>'分每页数据数量必须为正整数'
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 20:14
 */

namespace app\api\validate;


class Idvaliadet extends BaseValidate
{
    protected $rule = [
        'id' => 'require|Isidint'
    ];
    protected $message=[
        'id'=>'值为必须值且必须为整数'
    ];
}
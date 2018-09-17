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
    protected $rule=[
        'id'=>'require|Isidint'
    ];
    protected function Isidint($value,$rule='',$data='',$field=''){
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            return true;
        }else{
            return $field.'值为必须值且必须为整数';
        }
    }
}
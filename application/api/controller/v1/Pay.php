<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/10/17
 * Time: 8:23
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\Idvaliadet;

class Pay extends BaseController
{
    protected $beforeActionList=[
        'NeedUser'=>['only'=>'PrePayOrder']
    ];
    public function PrePayOrder($orderid=''){
        (new Idvaliadet())->goCheck();
        
    }
}
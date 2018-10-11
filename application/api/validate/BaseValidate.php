<?php
/**
 * Created by PhpStorm.
 * User: Fanji
 * Date: 2018/9/15
 * Time: 22:03
 */

namespace app\api\validate;


use app\exception\ParamExcepotion;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        //获取参数
        $request=Request::instance();
        $params=$request->param();
        //批量验证参数
        $result=$this->batch()->check($params);
        if(!$result){
            //获取具体的验证错误信息自定义并抛出
            $err=new ParamExcepotion([
                'msg'=>$this->error,
                'code'=>400,
                'errorcode'=>10002
            ]);
            throw $err;
        }else{
            return true;
        }
    }
    //自定义验证参数id为整数
    protected function Isidint($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }
    //自定义验证参数code
    protected function Isnotempty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return false;
        } else {
            return true;
        }
    }
    //自定义验证手机号
    protected function IsMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    //对传递的参数进行过滤
    public function getPostDataByRule($array){
        if(array_key_exists('user_id',$array) || array_key_exists('uid',$array)){
            throw new ParamExcepotion([
                'msg'=>'参数中含有非法参数user_id或uid'
            ]);
        }
        $newarray=[];
        //获取对应验证器中设置的参数规则对应的参数名进行遍历
        foreach ($this->rule as $key=>$value){
            $newarray[$key]=$array[$key];
        }
        return $newarray;
    }
}
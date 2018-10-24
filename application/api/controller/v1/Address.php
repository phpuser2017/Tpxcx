<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/11
 * Time: 10:38
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\User;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\validate\AddressEdit;
use app\exception\SuccessMsg;
use app\exception\UserException;

class Address extends BaseController
{
    //前置操作验证基础权限
    protected $beforeActionList=[
        'CheckBaseScope'=>['only'=>'GetAddress,EditAddress']
    ];
    /*
     * 获取地址
     * */
    public function GetAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }
    /**
     * 新增或者编辑地址
     * */
    public function EditAddress(){
        $addressValidate=new AddressEdit();
        $addressValidate->goCheck();
        //根据token获取uid
        $uid=TokenService::getCurrentUid();
        //根据uid获取用户数据，不存在抛出异常
        $haveuser=User::get($uid);
        if(!$haveuser){
            throw new UserException();
        }
        //根据用户id查询是否有地址(通过关联关系读取)
        $addressdata=$haveuser->getuseraddress;
        //获取用户提交的地址信息(经过验证过滤)
        $postdata=$addressValidate->getPostDataByRule(input('post.'));
        /*根据用户地址信息是否存在判断是新增还是更新操作
         **/
        if(!$addressdata){
            //没有地址新增(关联模型)
            $haveuser->getuseraddress()->save($postdata);
        }else{
            //有地址更新,直接读取值进行更新
            $haveuser->getuseraddress->save($postdata);
        }
        //json序列化返回，否则为对象会报错
        return json(new SuccessMsg(),201);
    }
    
}
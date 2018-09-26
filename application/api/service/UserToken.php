<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:21
 */

namespace app\api\service;


use app\api\model\User;
use app\exception\WxException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxappid;
    protected $wxappsecret;
    protected $loginurl;
    function __construct($code)
    {
        $this->code=$code;
        $this->wxappid=config('wxconfig.appid');
        $this->wxappsecret=config('wxconfig.secret');
        //https://api.weixin.qq.com/sns/jscode2session?appid=wxcf0ae424dbe07b84&secret=43f15458902b651cac9344fff7b90c0c&js_code=06102OP01Cf3KZ1He0S01MHRP0102OPq&grant_type=authorization_code
        $this->loginurl=sprintf(config('wxconfig.loginurl'),$this->wxappid,$this->wxappsecret,$this->code);
    }
    //获取openid、session_key
    public function tokenget(){
        $login=curl_get($this->loginurl);
        $result=json_decode($login,true);
       /*
         *session_key='SHarVNylaOk+B+3rRx8I6Q==',
         * expires_in=7200,
         * openid='oJfz00OI4TOh8IOMj8Cw1mMwBi3I'
        */
       //判断接口是否访问成功
        if(empty($result)){
            throw new Exception('获取openid、session_key失败');
        }else{
            //判断是否成功获取到openid
            if(array_key_exists('errcode',$result)){
                $this->Loginerror($result);
            }else{
                //获取到openid进行授权
                $this->grant($result);
            }
        }
    }
    //登陆失败异常处理
    private function Loginerror($result){
        throw new WxException([
            'msg'=>$result['errmsg'],
            'errorCode'=>$result['errcode']
        ]);
    }
    //授权
    private function grant($result){
        //数据库查询拿到的openid是否存在
        $getuserr=User::getuserbyopenid($result['openid']);
        if($getuserr){
            //存在取出id
            $uid=$getuserr->id;
        }else{
           //不存在则新增一条数据
            $uid=$this->insertuser($result['openid']);
        }
       $cachevalue=$this->preparecachevalue($result,$uid);
    }
    //添加数据
    private function insertuser($openid){
        $new=User::create([
            'openid'=>$openid
        ]);
        return $new->id;
    }
    //准备生成令牌需要的值
    private function preparecachevalue($result,$uid){
        $cachedata=$result;
        $cachedata['uid']=$uid;
        $cachedata['scope']=16;
    }
}
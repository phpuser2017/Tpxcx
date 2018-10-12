<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:21
 */

namespace app\api\service;


use app\api\model\User;
use app\enum\ScopeEnum;
use app\exception\WxException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $appid;
    protected $appsecret;
    protected $loginurl;
    //微信获取openid的配置
    function __construct($code)
    {
        $this->code=$code;
        $this->appid=config('wxconfig.appid');
        $this->appsecret=config('wxconfig.secret');
        //https://api.weixin.qq.com/sns/jscode2session?appid=wxcf0ae424dbe07b84&secret=43f15458902b651cac9344fff7b90c0c&js_code=06102OP01Cf3KZ1He0S01MHRP0102OPq&grant_type=authorization_code
        $this->loginurl=sprintf(config('wxconfig.loginurl'),$this->appid,$this->appsecret,$this->code);
    }
    //获取openid、session_key
    public function getopenid(){
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
                return $this->grant($result);
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
        //将数据写入缓存（加快访问速度、减小数据库压力）
        //数组形式：key=token,value=result(获取到的token信息),uid(用户唯一id),scope(自定义权限级别)
        //通过token可以在缓存中获取到对应的token信息、权限级别等用户信息
        $cachevalue=$this->preparecachevalue($result,$uid);
        //生成token写入缓存并将token返回
        $token=$this->savecachedata($cachevalue);
        return $token;
    }
    //添加数据
    private function insertuser($openid){
        $new=User::create([
            'openid'=>$openid
        ]);
        return $new->id;
    }
    /*准备生成令牌需要的值
     * @result(获取到的token信息)
     * @uid(用户唯一id)
     * @scope(自定义权限级别)
     * */
    private function preparecachevalue($result,$uid){
        $cachedata=$result;
        $cachedata['uid']=$uid;
        //scope 区别app与cms用户的管理权限(如:cms管理员可以删除商品而app用户不可以)
        $cachedata['scope']=ScopeEnum::User;
//        $cachedata['scope']=11;
        //将准备好的数据返回
        return $cachedata;
    }
    /*将数据写入缓存
     * */
    private function savecachedata($cachevalue){
        //产生token
        $key=self::generateToken();
        $value=json_encode($cachevalue);
        $token_vld=config('setting.token_vld');
        //保存到缓存
        $cache=cache($key,$value,$token_vld);
        if(!$cache){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorcode'=>'10005'
            ]);
        }
        //将令牌返回给客户端
        return $key;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 16:45
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    private $tokenurl;
    function __construct()
    {
        $ATurl=config('wxconfig.getAccessTokenUrl');
        $ATurl=sprintf($ATurl,config('wxconfig.appid'),config('wxconfig.secret'));
        $this->tokenurl=$ATurl;
    }
    
    /**
     * 优先获取缓存中的，微信access_token 2000次/天
     */
    public function get(){
        $AToken=$this->getFromCache();
        if(!$AToken){
            return $this->getFromServer();
        }else{
            return $AToken;
        }
    }
    //缓存中获取
    private function getFromCache(){
        $token=cache(config('wxconfig.ATCacheKey'));
        if(!$token){
            return $token;
        }
        return null;
    }
    //从微信服务器中获取
    private function getFromServer(){
        $token=curl_get($this->tokenurl);
        $token=json_decode($token,true);
        //获取access_token异常
        if(!$token){
            throw new Exception('获取access_token异常');
        }
        //错误
        if(!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
        cache(config('wxconfig.ATCacheKey'),$token,config('wxconfig.ATExpiresIn'));
        return $token['access_token'];
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 16:30
 */
return[
    'appid'=>'wxcf0ae424dbe07b84',
    'secret'=>'43f15458902b651cac9344fff7b90c0c',
    /*
     //西贝会员卡
     'appid'=>'wx201197f96cb080f4',
    'secret'=>'bad365e82a9c93d80b45df97b07c71ba',*/
    //微信支付
    'loginurl'=>'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
    'notify_url'=>$_SERVER['SERVER_NAME'].'api/v1/pay/wxpaynotify',
    'shopmchid'=>'1875997610',
    'shopkey'=>'33f123413d9b6658d88d2e104bad4c7f',
    //微信消息模板
    'getAccessTokenUrl'=>'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    'ATExpiresIn'=>'7000',
    'ATCacheKey'=>'accessToken',
    'MsgId'=>'-zLGuF829cyRZv1s1PqjLE9jUj8CLx-KOnuvXNOrufE',
    'sendUrl'=>'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s'
];
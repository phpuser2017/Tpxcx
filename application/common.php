<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/*http请求
 * url 请求地址
 * httpcode 返回状态码
*/
function curl_get($url,&$httpcode=0){
    $cu=curl_init();
    curl_setopt($cu,CURLOPT_URL,$url);
    curl_setopt($cu,CURLOPT_RETURNTRANSFER,1);
    //ssl证书验证，关闭
    curl_setopt($cu,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($cu,CURLOPT_CONNECTTIMEOUT,10);
    $fileconnect=curl_exec($cu);
    $httpcode=curl_getinfo($cu,CURLINFO_HTTP_CODE);
    curl_close($cu);
    return $fileconnect;
}

//function curl_get($url){
//    $curl = curl_init();
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
//    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//
//    $output = curl_exec($curl);
//    $info   = curl_getinfo($curl);
//    curl_close($curl);
//
//    if (isset($info['http_code']) && 200 == $info['http_code']) {
//        return $output;
//    }else{
//        return false;
//    }
//}
//
//function curl_post($url, $data = [], $header = []){
//    $curl = curl_init();
//    curl_setopt($curl, CURLOPT_URL, $url);
//    curl_setopt($curl, CURLOPT_POST, 1);
//    if (!empty($header)) {
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
//    }
//    curl_setopt($curl, CURLOPT_HEADER, 0);
//    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
//    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//
//    $output = curl_exec($curl);
//    $info   = curl_getinfo($curl);
//    curl_close($curl);
//
//    if (isset($info['http_code']) && 200 == $info['http_code']) {
//        return $output;
//    }else{
//        return false;
//    }
//}
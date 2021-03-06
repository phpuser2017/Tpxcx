<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
//
//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//];
use think\Route;
//动态api版本识别
//http://tp/api/v1/banner/1
Route::get('api/:version/banner/:id','api/:version.Banner/getbanner');//轮播图
//http://tp/api/v1/getthemes?ids=1,2,3
Route::get('api/:version/getthemes','api/:version.Theme/getthemes');//主题
//http://tp/api/v1/getthemes/1
Route::get('api/:version/getthemes/:id','api/:version.Theme/getthemedetail');//主题详情获取
//http://tp/api/v1/getcategory
Route::get('api/:version/getcategory','api/:version.Category/getCategories');//分类
//http://tp/api/v1/getnewproducts/1
Route::get('api/:version/getnewproducts/:count','api/:version.Product/getnewproducts');//最新商品获取
//http://tp/api/v1/getproducty/2
Route::get('api/:version/getproducty/:id','api/:version.Product/getallproduct');//分类中的商品
//http://tp/api/v1/producty/2
Route::get('api/:version/producty/:id','api/:version.Product/productdetail');//点击商品商品详情
//商品路由分组
/*Route::group('api/:version/producty',function (){
    //http://tp/api/v1/producty/new/1
    Route::get('/new/:count','api/:version.Product/getnewproducts');//最新商品获取
    //http://tp/api/v1/producty/bycategory/2
    Route::get('/bycategory/:id','api/:version.Product/getallproduct');//分类中的商品
    //http://tp/api/v1/producty/2
    Route::get('/:id','api/:version.Product/productdetail');//点击商品商品详情
});*/

//token http://tp/api/v1/token/user
Route::post('api/:version/token/user','api/:version.Token/getToken');//获取token
Route::post('api/:version/token/check','api/:version.Token/checkToken');//校验token
Route::post('api/:version/token/appcheck','api/:version.Token/getAppToken');//第三方token获取

//地址 http://tp/api/v1/addressedit
Route::post('api/:version/addressedit','api/:version.Address/EditAddress');//新增、编辑地址
Route::get('api/:version/getaddress','api/:version.Address/GetAddress');//获取地址

//订单
Route::post('api/:version/createorder','api/:version.Order/CreateOrder');//创建订单
Route::post('api/:version/getbrieforder','api/:version.Order/getBriefOrders');//我的订单[快照]
Route::get('api/:version/orderdetail/:id','api/:version.Order/OrderDetails');//订单详情
Route::get('api/:version/getallorder','api/:version.Order/getOrder');//cms获取我的订单
Route::put('api/:version/sendshop','api/:version.Order/SendShopSendMsg');//订单返货+微信推送
//支付
Route::post('api/:version/pay/prepay','api/:version.Pay/PrePayOrder');//预支付
Route::post('api/:version/pay/wxpaynotify','api/:version.Pay/ReceiveNotify');//微信支付回调
Route::post('api/:version/pay/testnotify','api/:version.Pay/redirectNotify');//微信支付回调调试转发
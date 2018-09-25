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

//Route::get('api/v1/banner/:id','api/v1.Banner/getbanner');
//动态api版本识别
Route::get('api/:version/banner/:id','api/:version.Banner/getbanner');//轮播图
Route::get('api/:version/getthemes','api/:version.Theme/getthemes');//主题
Route::get('api/:version/getthemes/:id','api/:version.Theme/getthemedetail');//主题详情获取
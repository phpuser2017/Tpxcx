<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/15
 * Time: 14:11
 */

namespace app\api\controller\v1;
use think\Validate;

class Banner
{
    /*
     * 获取指定id的banner信息
     * id 不同位置
     * url banner/:id
     * http GET
     * */
    public function getbanner($id){
        $data = [
            'name' => 'thinkphp',
            'email' => 'thinkphp@qq.com'
        ];
        $validate = new Validate([
            'name' => 'require|min:25',
            'email' => 'email'
        ]);
        $result=$validate->check($data);
        if (!$validate->check($data)) {
            dump($validate->getError());
        }
    }
}
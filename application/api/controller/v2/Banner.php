<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/15
 * Time: 14:11
 */

namespace app\api\controller\v2;

use app\api\model\Banner as BannerModel;
use app\api\validate\Idvaliadet;
use app\exception\BannerException;

class Banner
{
    /*
     * 获取指定id的banner信息
     * id 不同位置
     * url banner/:id
     * http GET
     * */
    public function getbanner($id)
    {
        
        return 'v2 api';
    }
}
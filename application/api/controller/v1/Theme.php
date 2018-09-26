<?php
/**
 * Created by PhpStorm.
<<<<<<< HEAD
 * User: fan
 * Date: 2018/09/25
 * Time: 14:53
=======
 * User: Fanji
 * Date: 2018/9/21
 * Time: 8:12
>>>>>>> 429fd28d766ff72f32d5cd8a476ddced02097709
 */

namespace app\api\controller\v1;

use app\api\model\Theme as ThemeModel;
use app\api\validate\Idscheck;
use app\api\validate\Idvaliadet;
use app\exception\ThemeException;

class Theme
{
    public function getthemes($ids=''){
        (new Idscheck())->goCheck();
        $ids=explode(',',$ids);
        //调用模型方法查询
        $themes = ThemeModel::getthemes($ids);
        if ($themes->isEmpty()) {
            throw new ThemeException();
        }
        return json($themes);
    }
    public function getthemedetail($id){
        (new Idvaliadet())->goCheck();
        //调用模型方法查询
        $product = ThemeModel::getproduct($id);
        if (!$product) {
            throw new ThemeException();
        }
        return json($product);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/09/26
 * Time: 10:46
 */

namespace app\api\controller\v1;
use app\api\model\Category as CategoryModel;
use app\exception\CategoryException;

class Category
{
    public function getCategories(){
        //查询所有分类及对应的头部图片（all([])空数组表示查询全部）
        $categories=CategoryModel::all([],'topicimg');
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}
import config from '../utils/config'
//引入基类
import { Base } from '../utils/base.js'
//继承基类
class CategoryModel extends Base {
  constructor() {
    //继承基类构造方法
    super()
  }
  //获取所有分类
  CategoryData(CallBack) {
    var params = {
      url: config.categorys,
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
  //获取某一分类下商品
  ProductData(id, CallBack) {
    var params = {
      url: config.getproducty+id,
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
}
export { CategoryModel }
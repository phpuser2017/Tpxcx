import config from '../utils/config'
//引入基类
import { Base } from '../utils/base.js'
//继承基类
class ProductModel extends Base {
  constructor() {
    //继承基类构造方法
    super()
  }
  //商品详情
  ProductDetail(id, CallBack) {
    var params = {
      url: config.productdetail + id,
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
}
export { ProductModel }
import config from '../utils/config'
//引入基类
import {Base} from '../utils/base.js'
//继承基类
class HomeModel extends Base{
  constructor(){
    //继承基类构造方法
    super()
  }
  //轮播图
  getBanners(id, CallBack){ 
    var params={
      url: config.banner + id,
      sCallback: function (res) {
        CallBack && CallBack(res.banneritems)
      }
    };
    this.Request(params);
  }
  //主题
  getThems(CallBack){
    var params = {
      url: config.thems+'?ids=1,2,3',
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
  //最新商品
  geProducts(count,CallBack) {
    var params = {
      url: config.newproducts + count,
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
}
export{HomeModel}
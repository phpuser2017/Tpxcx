import config from '../utils/config'
//引入基类
import { Base } from '../utils/base.js'
//继承基类
class ThemeModel extends Base {
  constructor() {
    //继承基类构造方法
    super()
  }
  //主题
  ThemeData(themeid, CallBack) {
    var params = {
      url: config.thems + '/'+themeid,
      sCallback: function (res) {
        CallBack && CallBack(res)
      }
    };
    this.Request(params);
  }
}
export { ThemeModel }
import config from '../utils/config'
//引入基类
import { Base } from '../utils/base.js'

class AddressModel extends Base {
  /**
   * 组装地址信息
   * provinceName  微信选择控件返回结果
   * province      API返回的结果
   */
  setAddress(res) {
    var province = res.provinceName || res.province,
        city = res.cityName || res.city,
        country = res.countyName || res.country,
        detail = res.detailInfo || res.detail;
    var totalDetail = city + country + detail;
    //是否是直辖市
    if (!this.isCenterCity(province)) {
      //不是直辖市需要增加省
      totalDetail = province + totalDetail;
    };
    return totalDetail;
  }
  /*是否为直辖市*/
  isCenterCity(name) {
    var centerCitys = ['北京市', '天津市', '上海市', '重庆市'],
        flag = centerCitys.indexOf(name) >= 0;
    return flag;
  }
}
export {
  AddressModel
}
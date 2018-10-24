import config from '../utils/config'
//引入基类
import { Base } from '../utils/base.js'

class AddressModel extends Base {
  /**
   * 获取数据库地址
   */
  getAddress(callback){
    var that=this
    var params = {
      url: config.getaddress,
      sCallback: function (res) {
        if(res){
          res.totalDetail=that.setAddress(res)
          callback && callback(res);
        }
      }
    };
    this.Request(params);
  }
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
  /**
   * 将填写选择的地址保存到数据库
   */
  addAddress(res,CallBack) {
    var data=this._getaddress(res)
    var params = {
      url: config.addaddress,
      type:'POST',
      data:data,
      sCallback: function (res) {
        CallBack && CallBack(true,res)
      },
      eCallback: function (res) {
        CallBack && CallBack(false, res)
      }
    };
    this.Request(params);
  }
  //将选择的地址转为数据库字段
  _getaddress(res){
    return {
      name:res.userName,
      mobile:res.telNumber,
      province: res.provinceName,
      city:res.cityName,
      country: res.countyName,
      detail:res.detailInfo
    }
  }
}
export {
  AddressModel
}
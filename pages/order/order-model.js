import config from '../utils/config.js'
import {Base} from '../utils/base.js'

class OrderModel extends Base{
  constructor(){
    super()
    this._storagekey='neworder'
  }
  /**
   * 下单
   */
  makeOrder(params,callback){
    var that=this
    var allparams={
      url: config.createorder,
      type:'POST',
      data: { products:params},
      sCallback:function(res){
        callback && callback(data)
        that.updataStorage(true)
      }
    }
  }
  /**
  * 缓存更新
  */
  updataStorage(data) {
    wx.setStorageSync(this._storagekey, data)
  }

}
export {OrderModel}
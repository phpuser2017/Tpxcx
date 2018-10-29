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
        callback && callback(res)
        //生成订单添加标记
        that.updateneworder(true)
      }
    }
    this.Request(allparams)
  }
  /**
   * 支付
   * @callback(param)
   *  0:商品库存不足无法支付
   *  1:支付失败或者取消
   *  2:支付成功
   */
  gopay(orderid,callback){
    var params={
      url: config.pay,
      type: 'POST',
      data: { id: orderid },
      sCallback: function (res) {
        if(data.timeStamp){
          //服务器返回支付参数，可以支付
          wx.requestPayment({
            'timeStamp': data.timeStamp.toString(),
            'nonceStr': data.nonceStr,
            'package': data.package,
            'signType': data.signType,
            'paySign': data.paySign,
             success: function (res) {
               callback && callback(1)
            },
            fail:function(){
              callback && callback(2)
            }
            })
        }else{
          callback && callback(0)
        }
      }
    }
    this.Request(params)
  }

  //获取订单信息[详情]
  getOrderInfoById(id,callback){
    var that=this
    var params = {
      url: config.orderdetail+id,
      sCallback: function (res) {
        callback && callback(res)
      }
    }
    this.Request(params)
  }
  /**
   * 获取所有订单
   */
  getMyoedres(pageindex,callback){
    var that = this
    var params = {
      url: config.getbrieforder,
      type: 'POST',
      data: {page: pageindex},
      sCallback: function (res) {
        callback && callback(res)
      }
    }
    this.Request(params)
  }
  /*是否有新的订单*/
  hasNewOrder() {
    var flag = wx.getStorageSync(this._storagekey);
    return flag == true;
  }
  //生成、取消新订单
  updateneworder(data){
    wx.setStorageSync(this._storagekey, data)
  }

}
export {OrderModel}
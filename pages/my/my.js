// pages/my/my.js
import config from '../utils/config.js'
import { MyModel } from '../my/my-model.js';
var mymodel = new MyModel();
import { AddressModel } from '../utils/address-model.js';
import { OrderModel } from '../order/order-model.js';
var addressmodel = new AddressModel();
var ordermodel = new OrderModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    pageIndex:1,
    orderArr:[],
    isLoadedAll:false,//是否加载完全部数据
    loadingHidden: false,

  },
  /**
  * 生命周期函数--监听页面显示
  */
  onShow: function () {
    //更新订单,相当自动下拉刷新,只有  非第一次打开 “我的”页面，且有新的订单时 才调用。
    var newOrderFlag = ordermodel.hasNewOrder();
    if (this.data.loadingHidden && newOrderFlag) {
      this.refresh()
    }
  },
  //从数据库重新获取数据
  refresh:function(){
    var that=this
    //重置显示的订单数据
    that.setData({
      orderArr: [],
      loadingHidden: true
    })
    //重新获取数据
    that._getorderarr(()=>{
      that.data.isLoadedAll = false;
      that.data.pageIndex = 1;
      //获取数据后将新订单标记取消
      ordermodel.updateneworder(false)
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this._loaddata()
  },
  //获取数据
  _loaddata:function(){
    var that=this
    //获取个人信息
    mymodel.getUserinfo((userinfo)=>{
      that.setData({
        userInfo: userinfo
      })
    })
    //获取地址
    addressmodel.getAddress((address)=>{
      that.setData({
        addressInfo: address
      })
    })
    //获取订单
    that._getorderarr()
  },
  _getorderarr:function(callback){
    var that = this
    ordermodel.getMyoedres(that.data.pageIndex, (myorders) => {
      var orderdata = myorders.data
      if (orderdata.length > 0) {
        that.data.orderArr.push.apply(that.data.orderArr, orderdata);  //将页面加载数据与分页数据合并
        that.setData({
          orderArr: that.data.orderArr,
          loadingHidden: true
        });
      } else {
        that.data.isLoadedAll = true;  //已经全部加载完毕
        that.data.pageIndex = 1;
      }
      callback && callback()
    })
   
  },
  //添加地址
  addaddress: function () {
    var that = this
    wx.chooseAddress({
      success: function (res) {
        console.log(res)
        var address = {
          name: res.userName,
          mobile: res.telNumber,
          totalDetail: addressmodel.setAddress(res)
        }
        that.setData({
          addressInfo: address
        })
        addressmodel.addAddress(res, (flag) => {
          if (!flag) {
            that.showtips('操作提示', '地址信息更新失败')
          }
        })
      }
    })
  },
  /**
   * 页面上拉触底加载下一页数据
   */
  onReachBottom: function () {
    if(!this.data.isLoadedAll){
      this.data.pageIndex++;
      this._getorderarr();
    }
  },
  //订单详情
  showOrderDetailInfo:function(e){
    var id=mymodel.getBindvalu(e,'id')
    wx.navigateTo({
      url: '../order/order?from=order&id=' + id
    })
  },
  //付款
  rePay:function(e){
    var id = mymodel.getBindvalu(e, 'id'),
      index = mymodel.getBindvalu(e, 'index');//用来更新某条订单的状态

    //online 上线实例，屏蔽支付功能
    if (config.switchpay) {
      this._execPay(id, index);
    } else {
      this.showtips('支付提示', '本产品仅用于演示，支付系统已屏蔽');
    }
  },
  /*支付*/
  _execPay: function (id, index) {
    var that = this;
    ordermodel.gopay(id, (statusCode) => {
      if (statusCode > 0) {
        var flag = statusCode == 2;

        //更新订单显示状态
        if (flag) {
          that.data.orderArr[index].status = 2;//将对应订单状态改为已支付
          that.setData({
            orderArr: that.data.orderArr
          });
        }

        //跳转到 成功页面
        wx.navigateTo({
          url: '../payresult/payresult?id=' + id + '&ispayok=' + flag + '&from=my'
        });
      } else {
        that.showtips('支付失败', '商品已下架或库存不足');
      }
    });
  },
  /**
 * 提示信息
 * @title 弹出框标题
 * @content 弹出框内容
 * @flag 是否跳转到我的页面
 */
  showtips: function (title, content, flag) {
    wx.showModal({
      title: title,
      content: content,
      showCancel: false,
      success: function (re) {

      }
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

 

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
// pages/order/order.js
import {CartModel} from '../cart/cart-model.js';
import {AddressModel} from '../utils/address-model.js';
var cartmodel=new CartModel();
var addressmodel=new AddressModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var ordrePrice = options.orderprice,
        frompage = options.from;
    var orderProducts = cartmodel.StorageCartData(true)
    this.setData({
      orderproducts: orderProducts,
      ordreprice: ordrePrice,
      orderStatus:0
    })
  },
  //添加地址
  addaddress:function(){
    var that=this
    wx.chooseAddress({
      success:function(res){
        var address={
          name:res.userName,
          mobile:res.telNumber,
          totalDetail: addressmodel.setAddress(res)
        }
        that.setData({
          adress:address
        })
      }
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
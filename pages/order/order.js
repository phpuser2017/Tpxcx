// pages/order/order.js
import {CartModel} from '../cart/cart-model.js';
import {AddressModel} from '../utils/address-model.js';
import {OrderModel} from '../order/order-model.js';
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
    var that=this
    var ordrePrice = options.orderprice,
        frompage = options.from;
    var orderProducts = cartmodel.StorageCartData(true)
    //获取地址
    addressmodel.getAddress((res)=>{
      that.setData({
        adress:res
      })
    })
    
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
        console.log(res)
        var address={
          name:res.userName,
          mobile:res.telNumber,
          totalDetail: addressmodel.setAddress(res)
        }
        that.setData({
          adress:address
        })
        addressmodel.addAddress(res,(flag)=>{
          if(!flag){
            that.showtips('操作提示','地址信息更新失败')
          }
        })
      }
    })
  },
  /**
   * 提示信息
   * @title 弹出框标题
   * @content 弹出框内容
   * @flag 是否跳转到我的页面
   */
  showtips:function(title,content,flag){
    wx.showModal({
      title: title,
      content: content,
      showCancel:false,
      success:function(re){
        if(flag){
          wx.switchTab({
            url: '../my/my',
          })
        }
      }
    })
  },
  /**
   * 支付
   */
  pay:function(){

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
// pages/product/product.js
import { ProductModel } from 'product-model.js';
var productmodel = new ProductModel();
import {CartModel} from '../cart/cart-model.js';
var cartmodel=new CartModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadingHidden: false,//加载中
    countsArray: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],//显示商品可选择范围
    productCounts: 1,//默认选择商品数量
    infoIndex: 0,//详情
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var id=options.id
    this.setData({
      id:id
    })
    this._loaddata()
  },
  //获取商品详情
  _loaddata:function(){
    productmodel.ProductDetail(this.data.id,(res) => {
      this.setData({
        //购物车内商品数量
        cartTotalCounts: cartmodel.getCartShopCounts(false),
        product: res,
        loadingHidden: true
      })
    })
  },
  //跳转购物车
  tocart:function(){
    wx.switchTab({
      url: '../cart/cart',
    })
  },
  //选择购买数目
  bindPickerChange: function (e) {
    this.setData({
      productCounts: this.data.countsArray[e.detail.value],
    })
  },

  //切换详情面板
  infochange: function (event) {
    var index = productmodel.getBindvalu(event, 'index');
    this.setData({
      infoIndex: index
    });
  },
  //加入购物车
  addCart:function(e){
    //添加商品
    this.preCart()
    //添加商品动画
    this._flyToCartEffect(e)
    //实时更新购物车数量
    this.setData({
      cartTotalCounts: cartmodel.getCartShopCounts(false)
    })
  },
  //商品添加数组组装
  preCart:function(){
    var addproduct={}
    var keys=['id','name','main_img_url','price']
    for(var key in this.data.product){
      if(keys.indexOf(key)>=0){
         addproduct[key]=this.data.product[key]
      }
    }
    //调用购物车模型进行商品添加
    cartmodel.add(addproduct,this.data.productCounts)
  },
  /*加入购物车动效*/
  _flyToCartEffect: function (events) {
    //获得当前点击的位置，距离可视区域左上角
    var touches = events.touches[0];
    var diff = {
      x: '25px',
      y: 25 - touches.clientY + 'px'
    },
      style = 'display: block;-webkit-transform:translate(' + diff.x + ',' + diff.y + ') rotate(350deg) scale(0)';  //移动距离
    this.setData({
      isFly: true,
      translateStyle: style
    });
    var that = this;
    setTimeout(() => {
      that.setData({
        isFly: false,
        translateStyle: '-webkit-transform: none;',  //恢复到最初状态
        isShake: true,
      });
      setTimeout(() => {
        var counts = that.data.cartTotalCounts + that.data.productCounts;
        that.setData({
          isShake: false,
          cartTotalCounts: counts
        });
      }, 200);
    }, 1000);
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
// pages/product/product.js
import { ProductModel } from 'product-model.js';
var productmodel = new ProductModel();
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
  _loaddata:function(){
    productmodel.ProductDetail(this.data.id,(res) => {
      this.setData({
        product: res,
        loadingHidden: true
      })
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
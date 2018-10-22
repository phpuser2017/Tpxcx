// pages/home/hone.js
import {HomeModel} from 'home-model.js';
var homemodel = new HomeModel();
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
    this._getbanner()
  },
  _getbanner:function(){
    var id=1;
    // var banners=homemodel.getBanners(id,this.CallBack);
    //轮播图
    homemodel.getBanners(id,(res)=>{
      this.setData({
        banners:res
      })
    });
    //主题
    homemodel.geThems((res) => {
      this.setData({
        thems: res
      })
    });
    //最新视频
    var count=6
    homemodel.geProducts(count,(res) => {
      this.setData({
        newproducts: res
      })
    });
  },
  // CallBack:function(res){
  //   console.log(res)
  // },
  //点击轮播图跳转商品
  toproduct:function(e){
    var keyword = homemodel.getBindvalu(e,'keyword');
    wx.navigateTo({
      url: '../product/product'+'?keyword='+keyword,
    })
  },
  //点击主题
  totheme:function(e){
    var themeid=homemodel.getBindvalu(e,'themeid')
    var name=homemodel.getBindvalu(e,'name')
    wx.navigateTo({
      url: '../theme/theme'+'?themeid='+themeid+'&name='+name,
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
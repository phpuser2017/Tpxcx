// pages/category/category.js
import { CategoryModel } from 'category-model.js';
var categorymodel = new CategoryModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    transClassArr: ['tanslate0', 'tanslate1', 'tanslate2', 'tanslate3', 'tanslate4', 'tanslate5'],
    currentMenuIndex: 0,
    loadingHidden: false,
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this._loaddata()
  },
  _loaddata: function (callback) {
    var that=this
    categorymodel.CategoryData((res) => {
      that.setData({
        categorys: res
      })
      categorymodel.ProductData(res[0].id,(data)=>{
        var dataObj= {
          procucts: data,
          topImgUrl: res[0].topicimg.url,
          title: res[0].name
        };
        that.setData({
          loadingHidden: true,
          categoryInfo0:dataObj
        });
      });
      callback && callback();
    })
  },
  /*切换分类*/
  changeCategory: function (event) {
    var index = categorymodel.getBindvalu(event, 'index'),
      id = categorymodel.getBindvalu(event, 'id')
    this.setData({
      currentMenuIndex: index
    });

    //如果数据是第一次请求
    if (!this.isLoadedData(index)) {
      var that = this;
      categorymodel.ProductData(id, (data) => {
        that.setData(
          that.getDataObjForBind(index, data)
        );
      });
    }
  },
  //是否第一次访问该分类 categoryInfo0、categoryInfo1、categoryInfo2 ...
  isLoadedData: function (index) {
    if (this.data['categoryInfo' + index]) {
      return true;
    }
    return false;
  },
  getDataObjForBind: function (index, data) {
    var obj = {},
      arr = [0, 1, 2, 3, 4, 5],
      baseData = this.data.categorys[index];
    for (var item in arr) {
      if (item == arr[index]) {
        obj['categoryInfo' + item] = {
          procucts: data,
          topImgUrl: baseData.img.url,
          title: baseData.name
        };
        return obj;
      }
    }
  },

  getProductsByCategory: function (id, callback) {
    categorymodel.ProductData(id, (data) => {
      callback && callback(data);
    });
  },

  /*跳转到商品详情*/
  onProductsItemTap: function (event) {
    var id = categorymodel.getBindvalu(event, 'id');
    wx.navigateTo({
      url: '../product/product?id=' + id
    })
  },

  /*下拉刷新页面*/
  onPullDownRefresh: function () {
    this._loaddata(() => {
      wx.stopPullDownRefresh()
    });
  },

  //分享效果
  onShareAppMessage: function () {
    return {
      title: '零食商贩 Pretty Vendor',
      path: 'pages/category/category'
    }
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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  }
})
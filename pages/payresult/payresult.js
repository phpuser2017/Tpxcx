// pages/payresult/payresult.js
Page({

  /**
   * 页面的初始数据
   */
  data: {

  },
  onLoad: function (options) {
    //获取支付结果、订单id、跳转来源
    this.setData({
      payResult: options.ispayok,
      id: options.id,
      from: options.from
    });
  },
  //查看订单
  viewOrder: function () {
    if (this.data.from == 'my') {
      wx.redirectTo({
        url: '../order/order?from=order&id=' + this.data.id
      });
    } else {
      //返回上一级
      wx.navigateBack({
        delta: 1
      })
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
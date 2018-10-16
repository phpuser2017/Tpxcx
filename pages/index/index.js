//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
  },

  onLoad: function () {
  },
  //方法
  gettoken:function(){
    wx.login({
      success:function(res){
        console.log(res.code)
        //获取token
        let url = app.config.getToken;
        var data = {
          code: res.code
        }
        app.httpPost(url, data).then(function (re) {
          if(re.data.code==1){
            wx.setStorageSync('token', re.data.token)
          }
        })
      },
      fail: function () {}
    })
    
  },
  getsupertoken: function () {

  },
  pay: function () {
    var that = this
    let url = app.config.createorder;
    let data = {
      products:[
        { product_id: 1, count: 2 },
        { product_id: 2, count: 3 }
      ]
    }
    app.httpPost(url, data).then(function (re) {
      console.log(re)
      // if (re.data.code == 1) {
      //   wx.setStorageSync('token', re.data.token)
      // }
    })

  },
  send: function () {

  },
  fromid: function () {

  },
  checklogin: function () {
    wx.checkSession({
      success:function(){
        console.log('login in')
      },
      fail: function () {
        console.log('no login')
      }
    })
  },

})

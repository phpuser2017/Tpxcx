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
          console.log(re.data)
        })
      },
      fail: function () {}
    })
    
  },
  getsupertoken: function () {

  },
  pay: function () {

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

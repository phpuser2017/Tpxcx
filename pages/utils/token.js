import config from '../utils/config.js';

class TokenModel {
  /**
   * 验证token
   */
  verifyToken() {
    var token = wx.getStorageSync('token');
    if (!token) {
      this.getToken();
    }
    else {
      this._checkToken(token);
    }
  }
  /**
   * 服务器校验token
   */
  _checkToken(token) {
    var that = this;
    wx.request({
      url: config.Apiurl + config.checktoken,
      method: 'POST',
      data: {
        token: token
      },
      success: function (res) {
        var valid = res.data.checktoken;
        //服务器缓存中未找到token则重新获取
        if (!valid) {
          that.getToken();
        }
      }
    })
  }
  /**
   * 登陆服务器获取token
   */
  getToken(callBack) {
    var that = this;
    wx.login({
      success: function (res) {
        wx.request({
          url: config.Apiurl + config.gettoken,
          method: 'POST',
          data: {
            code: res.code
          },
          success: function (res) {
            wx.setStorageSync('token', res.data.token);
            callBack && callBack(res.data.token);
          }
        })
      }
    })
  }
}

export { TokenModel };
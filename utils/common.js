let promise = require('../utils/promise.js'); 
import Config from '../etc/config';
// promise方法
function wxPromoise (fn){
  return function (obj = {}) {
    return new Promise((resolve, reject) => {
      obj.success = function (res) {
        //成功
        resolve(res);
      }
      obj.fail = function (res) {
        //失败
        reject(res)
      }
      fn(obj)
    })
  }
}
//无论promise对象最后状态如何都会执行
Promise.prototype.finally = function (callback) {
  let P = this.constructor;
  return this.then(
    value => P.resolve(callback()).then(() => value),
    reason => P.resolve(callback()).then(() => { throw reason })
  );
}

// get方法封装 url代表链接 data代表传值参数
function getRequest (url,data) {
  var getRequest = wxPromoise(wx.request);
  return getRequest({
    url: Config.ApiPath + url,
    method: 'GET',
    data: data,
    header: {
      'Content-Type': 'application/json',
      'token': wx.getStorageSync('token')
    }
  })
}

// post方法封装 url代表链接 data代表传值参数
function postRequest (url, data) {
  var postRequest = wxPromoise(wx.request)
  return postRequest({
    url: Config.ApiPath + url,
    method: 'POST',
    data: data,
    header: {
      'Content-Type': 'application/json',
      'token': wx.getStorageSync('token')
    },
  })
}

module.exports = {
  postRequest        : postRequest,        // request请求
  getRequest         : getRequest,         // request请求
}
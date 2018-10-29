import {Base} from '../utils/base.js'
import config from '../utils/config.js'
class MyModel extends Base{
  constructor(){
    super()
  }
  getUserinfo(callback){
    var that=this
    wx.login({
      success:function(){
        wx.getUserInfo({
          success:function(res){
            callback && callback(res.userInfo)
          },
          fail:function(){
            callback && callback({
              avatarUrl:'../../imgs/icon/user@default.png',
              nickName:'小商城'
            })
          }
        })
      }
    })
  }
}
export{MyModel}
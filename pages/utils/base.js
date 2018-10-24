import config from './config';
import {TokenModel } from '../utils/token.js';
class Base{
  constructor(){
    this.apiurl=config.Apiurl 
  }
  /**
   * url访问
   * @norevisit=true 不做未授权重试(不再进行从服务器校验获取token重新获取数据操作)
   */
  Request(params,norevisit){
    var that=this
    var url=this.apiurl+params.url;
    if(!params.type){
      params.type='GET'
    }
    wx.request({
      url: url,
      data:params.data,
      method:params.type,
      header:{
        'content-type':'application/json',
        'token':wx.getStorageSync('token')
      },
      success:function(res){
        var code=res.statusCode.toString(),
            codefirst=code.charAt(0);//获取状态码的第一个数值
        if(codefirst=='2'){
          params.sCallback && params.sCallback(res.data)          
        }else{
          if(code=='401'){
            //重新获取token

            //携带新token再次访问返回401的api获取数据(只允许重新调用一次)
            if (!norevisit){
              that.revisitapi(params)
            }
          }
          if (norevisit){
            params.eCallback && params.eCallback(res.data)
          }  
        }
        // if (params.sCallback){
        //   params.sCallback(res)
        // }
      },
      fail:function(err){
        params.eCallback && params.eCallback(err.data)
      }
    }) 
  }
  //获取事件绑定元素值
  getBindvalu(event,key){
    return event.currentTarget.dataset[key];
  }
  /**
   * 获取token后携带新token再次访问返回401的api获取数据
   */
  revisitapi(params){
    var tokenmodel=new TokenModel()
    tokenmodel.getToken((token)=>{
      this.Request(params,true)
    })
  }
}
export{Base}
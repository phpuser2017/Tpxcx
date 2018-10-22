import config from './config'
class Base{
  constructor(){
    this.apiurl=config.Apiurl 
  }
  //url请求方法
  Request(params){
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
        params.sCallback && params.sCallback(res.data)
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
}
export{Base}
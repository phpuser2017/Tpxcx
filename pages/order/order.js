// pages/order/order.js
import {CartModel} from '../cart/cart-model.js';
import {AddressModel} from '../utils/address-model.js';
import {OrderModel} from '../order/order-model.js';
var cartmodel=new CartModel();
var addressmodel=new AddressModel();
var ordermodel = new OrderModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    id:null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    var frompage = options.from
    if (frompage=='cart'){
      that._fromcart(options.orderprice)
    }else{
      that._frommy(options.id)
      that.setData({
        id: options.id
      })
    }
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    //支付成功或失败页面返回或者查看订单获取订单数据
    if (this.data.id) {
      this._frommy(this.data.id)
    }
  },
  //来自购物车的跳转
  _fromcart: function (orderprice){
    var that=this
    var orderProducts = cartmodel.StorageCartData(true)
    //获取地址
    addressmodel.getAddress((res) => {
      that.setData({
        address: res
      })
    })
    this.setData({
      orderproducts: orderProducts,
      ordreprice: orderprice,
      orderStatus: 0
    })
  },
  //来自我的页面
  _frommy:function(id){
    if (id) {
      var that = this;
      //获取订单信息
      ordermodel.getOrderInfoById(id, (data) => {
        that.setData({
          orderStatus: data.status,//订单状态
          orderproducts: data.snap_items,//订单快照
          ordreprice: data.total_price,//订单总价
          basicInfo: {
            orderTime: data.create_time,//订单创建时间
            orderNo: data.order_no//订单号
          },
        });
        // 快照地址- 组装地址信息
        var addressInfo = data.snap_address;
        addressInfo.totalDetail = addressmodel.setAddress(addressInfo);
        that.setData({
          address: addressInfo
        })
      });
    }
  },
  //添加地址
  addaddress:function(){
    var that=this
    wx.chooseAddress({
      success:function(res){
        console.log(res)
        var address={
          name:res.userName,
          mobile:res.telNumber,
          totalDetail: addressmodel.setAddress(res)
        }
        that.setData({
          address:address
        })
        addressmodel.addAddress(res,(flag)=>{
          if(!flag){
            that.showtips('操作提示','地址信息更新失败')
          }
        })
      }
    })
  },
  /**
   * 提示信息
   * @title 弹出框标题
   * @content 弹出框内容
   * @flag 是否跳转到我的页面
   */
  showtips:function(title,content,flag){
    wx.showModal({
      title: title,
      content: content,
      showCancel:false,
      success:function(re){
        if(flag){
          wx.switchTab({
            url: '../my/my',
          })
        }
      }
    })
  },
  /**
   * 支付方法
   */
  pay:function(){
    var that=this;
    if (!that.data.address){
      that.showtips('下单提示', '请填写您的收货地址')
      return;
    }
    if(that.data.orderStatus==0){
      that._makeorderpay()
    }else{
      that._hasorderpay()
    }
  },
  /**
   * 生成订单并支付
   */
  _makeorderpay:function(){
    var makeorderinfo=[],
      orderproducts = this.data.orderproducts; 
    //遍历购物车中商品组成下单需要的数组[id、counts]
    for(let i=0;i<orderproducts.length;i++){
        makeorderinfo.push({
          product_id:orderproducts[i].id,
          count:orderproducts[i].counts
        })
    }
    var that = this
    ordermodel.makeOrder(makeorderinfo,(res)=>{
      //库存检测通过生成订单
      if(res.pass){
        //保存订单id、设置订单不是从购物车获取的
        that.data.orderid=res.order_id;
        that.data.fromcart=false
        //支付
        that._gopay(res.order_id)
      }else{
        //创建订单失败
        that._makeorderfail(res)
      }
    })
  },
  /**
   * 支付
   */
  _gopay:function(id){
    var that=this
    ordermodel.gopay(id,(paystatus)=>{
      if(paystatus!=0){
        //商品库存足够且下单成功，将购物车中下单的商品删除
        that.deletefromcart()
        //是否支付成功 paystatus==2 即为支付成功
        var ispayok= paystatus==2
        wx.navigateTo({
          url: '../payresult/payresult?id='+id+'&ispayok='+ispayok+'&from=order',
        })
      }
    })
  },
  /**
   * 将下单成功且支付的商品从购物车删除
   */
  deletefromcart:function(){
    var productids=[],
      cartproducts = this.data.orderproducts;
    for(let i=0;i<cartproducts.length;i++){
      productids.push(cartproducts[i].id)
    }
    //购物车模型中删除数据
    cartmodel.deleteProduct(productids)
  },
  /**
   * 下单失败提示信息
   */
  _makeorderfail: function (data) {
    var nameArr = [],//商品名数组
        name = '',//商品名
        str = '',//最后返回的组装信息
        pArr = data.pdetailArray;
    for (let i = 0; i < pArr.length; i++) {
      //将库存不足的商品名记录
      if (!pArr[i].haveStock) {
        name = pArr[i].name;
        if (name.length > 15) {
          name = name.substr(0, 12) + '...';
        }
        nameArr.push(name);
        if (nameArr.length >= 2) {
          break;
        }
      }
    }
    str += nameArr.join('、');
    if (nameArr.length > 2) {
      str += ' 等';
    }
    str += ' 缺货';
    wx.showModal({
      title: '下单失败',
      content: str,
      showCancel: false,
      success: function (res) {

      }
    });
  },
  /**
   * 已有订单进行支付
   */
  _hasorderpay:function(){
    this._gopay(this.data.id)
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

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
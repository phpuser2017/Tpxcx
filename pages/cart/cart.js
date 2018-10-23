// pages/cart/cart.js
import {CartModel} from '../cart/cart-model.js';
var cartmodel=new CartModel();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    loadingHidden:false
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    //获取缓存中购物车所有数据
    var cartdata = cartmodel.StorageCartData()
    //购物车数据被选中的商品总数
    var shopselectedcounts = cartmodel.getCartShopCounts(true)
    //计算购物车内商品
    var selectedshops=this._ShopSelected(cartdata)
    this.setData({
      cartdata: cartdata,
      shopselectedcounts:selectedshops.selectShopcounts,
      selecttypecounts: selectedshops.selectTypecounts,
      sumselectprice: selectedshops.sumselectprice,
      loadingHidden:true
    })
  },
  /**
   * 计算购物车中选中商品的总数、商品种类总数、商品总价
   * @sumselectprice    购物车选中的商品总价
   * @selectShopcounts  购物车选中商品总数
   * @selectTypecounts  购物车选中商品种类总数
   */
  _ShopSelected:function(cartdata){
    var len=cartdata.length,
        sumselectprice=0,
        selectShopcounts=0,
        selectTypecounts=0;
    let multiple = 100;
    for (let i = 0; i < len; i++) {
      //避免浮点运算误差 0.05 + 0.01 = 0.060 000 000 000 000 005 的问题，乘以 100 *100
      if (cartdata[i].isselected) {
        //总价=单价*数量
        sumselectprice += cartdata[i].counts * multiple * Number(cartdata[i].price) * multiple;
        //商品总数
        selectShopcounts += cartdata[i].counts;
        //商品种类总数
        selectTypecounts++;
      }
    }
    return {
      sumselectprice: sumselectprice / (multiple * multiple),
      selectShopcounts: selectShopcounts,
      selectTypecounts: selectTypecounts
    }
  },
  //选中\取消选中商品
  toggleSelect:function(e){
    //切换选中状态时实时更改缓存中数据
    var id = cartmodel.getBindvalu(e,'id'),
        selectedstatus = cartmodel.getBindvalu(e,'selctedstatus'),
        index=this._getShopindex(id);
    //将选中状态取反
    this.data.cartdata[index].isselected=!selectedstatus
    //更新选择后数据计算
    this._UpdateCartStorageData()
  },
  //加减数量
  changeCounts:function(e){
    var id = cartmodel.getBindvalu(e, 'id'),
        changetype = cartmodel.getBindvalu(e, 'type'),
        index = this._getShopindex(id),
        counts=1;
    if(changetype=='add'){
      cartmodel.addShopnum(id)
    }else{
      counts=-1
      cartmodel.cutShopnum(id)
    }
    //更新data中的数据
    this.data.cartdata[index].counts += counts
    //更新选择后数据计算
    this._UpdateCartStorageData()
  },
  //删除商品
  delete:function(e){
    var id = cartmodel.getBindvalu(e, 'id'),
        index = this._getShopindex(id);
    this.data.cartdata.splice(index,1)//将选中下标为index的商品从购物车数据中删除
    //更新删除后数据计算
    this._UpdateCartStorageData()
    //更新缓存数据
    cartmodel.deleteProduct(id)
  },
  //全选、全不选
  toggleSelectAll:function(e){
    //选中的商品种类总数=购物车数据长度时即为全选
    //点击时全选的状态
    var allchoice = cartmodel.getBindvalu(e, 'status') == 'true' ? true : false,
        data = this.data.cartdata,
        len = data.length;
    for (let i = 0; i < len; i++) {
      ////根据全选状态更改购物车中商品选中的状态
      data[i].isselected=!allchoice
    }
    //更新选择后数据计算
    this._UpdateCartStorageData()
  },
  //下订单
  submitOrder:function(e){
    wx.navigateTo({
      url: '../order/order?orderprice=' + this.data.sumselectprice+'&from=cart',
    })
  },
  /**
   * 获取选择商品在缓存数组中的下标
   */
  _getShopindex:function(id){
    var data = this.data.cartdata,
        len=data.length;
    for(let i=0;i<len;i++){
      if(data[i].id==id){
        return i;
      }
    }
  },
  /**
   * 更新购物车商品数据
   * 选择商品或更改全选重新统计
   */
  _UpdateCartStorageData:function(){
    var newdata=this._ShopSelected(this.data.cartdata)
    //更新数据后页面显示数值重新赋值
    this.setData({
      cartdata: this.data.cartdata,
      shopselectedcounts: newdata.selectShopcounts,
      selecttypecounts: newdata.selectTypecounts,
      sumselectprice: newdata.sumselectprice
    })
  },
  onHide:function(){
    //页面隐藏将页面数据更新到缓存中
    cartmodel.updataStorage(this.data.cartdata)
  },
  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
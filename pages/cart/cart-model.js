import {Base} from '../utils/base.js'

class CartModel extends Base{
  constructor(){
    super()
    this._storageKeyname='cart'
  }
  /**
   * 给购物车添加商品
   * @item 商品对象
   * @counts 添加的商品数量
   * 判断购物车原有数据中是否存在要添加的商品
   *  有    该商品数量=原有数量+counts
   *  没有  新增商品，数量为counts
   */
  add(item,counts){
    //获取缓存中购物车数据
    var cartData=this.StorageCartData();
    //检测购物车数据有无要添加的商品
    var isShophasCart=this.StorageCartData(item.id,counts)
    if(!isShophasCart){
      item.counts=counts,
      item.isselected=true//新增商品默认选中
      cartData.push(item)//将商品加入购物车中
    }else{
      cartData[isShophasCart.index].counts+=counts
    }
    wx.setStorageSync(this._storageKeyname, cartData)
  }

  //从缓存中获取购物车数据
  StorageCartData(){
    var data = wx.getStorageSync(this._storageKeyname)
    if(!data){
      data=[]
    }
    return data;
  }
  //检测购物车数据中是否有要添加的商品数据
  _ShopHasCart(id,arr){
    var item,
      result = { index: -1 };
    for (let i = 0; i < arr.length; i++) {
      item = arr[i];
      if (item.id == id) {
        result = {
          index: i,
          data: item
        };
        break;
      }
    }
    return result;
  }
}
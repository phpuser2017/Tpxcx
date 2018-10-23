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
    var isShophasCart = this._ShopHasCart(item.id, cartData)
    if(isShophasCart.index==-1){
      item.counts=counts,
      item.isselected=true//新增商品默认选中
      cartData.push(item)//将商品加入购物车中
    }else{
      //已存在该商品则将其数量更新
      cartData[isShophasCart.index].counts+=counts
    }
    wx.setStorageSync(this._storageKeyname, cartData)
  }

  //从缓存中获取购物车数据
  StorageCartData(flag){
    var data = wx.getStorageSync(this._storageKeyname)
    if(!data){
      data=[]
    }
    //获取缓存中选中要下单的商品
    if(flag){
      var selectedProducts=[]
      for(let i=0; i<data.length; i++){
        if(data[i].isselected){
          selectedProducts.push(data[i])
        }
      }
      data=selectedProducts
    }
    return data;
  }
  /**
   * 检测购物车数据中是否有要添加的商品数据
   * @id 商品id
   * @arr 购物车中所有商品对象集
   */
  _ShopHasCart(id,arr){
    var item;
    var result = { index: -1 };
    for (let i = 0; i < arr.length; i++) {
      if (arr[i].id == id) {
        //商品存在，将其在数组的序号、数据对象返回
        result = {
          index: i,
          data: arr[i]
        };
        break;
      }
    }
    return result;
  }
  /**
   * 计算购物车中数据
   * shopselected false 表示不需要考虑商品是否被选中进行计算数量
   * shopselected true 表示需要考虑商品是否被选中进行计算数量
   */
  getCartShopCounts(shopselected){
    var alldata=this.StorageCartData();
    var allcounts=0;
    for (let i = 0; i < alldata.length; i++){
      if (shopselected){
        if(alldata[i].isselected){
          allcounts += alldata[i].counts;
        }
      }else{
        allcounts += alldata[i].counts;
      }
    }
    return allcounts;
  }
  /**
   * 增加商品
   */
  addShopnum(id){
    this._CartChangeCounts(id,1)
  }
  /**
   * 减少商品
   */
  cutShopnum(id) {
    this._CartChangeCounts(id, -1)
  }
  /**
   * 购物车中商品的增减
   *  id 商品id
   *  counts 商品数量
   */
  _CartChangeCounts(id,counts){
    var data=this.StorageCartData(),
        shophascart=this._ShopHasCart(id,data);
    if (shophascart.index != 1){
      //商品原来就在购物车中，需要把该商品数量更新
      if(shophascart.data.counts>1){
          data[shophascart.index].counts += counts
      }
    }
    //更新缓存，将新数据放入缓存
    this.updataStorage(data)
  }
  /**
   * 删除商品
   */
  deleteProduct(ids){
    //单个商品转数组
    if(!(ids instanceof Array)){
      ids=[ids]
    }
    var data=this.StorageCartData()
    for(let i=0;i<data.length;i++){
      //判断该商品是否在购物车数据中
      var isIncart=this._ShopHasCart(ids[i],data)
      if(isIncart.index!=-1){
        data.splice(isIncart.index, 1)//将选中下标为index的商品从购物车数据中删除
      }
    }
    //更新缓存，将新数据放入缓存
    this.updataStorage(data)
  }
  /**
   * 缓存更新
   */
  updataStorage(data){
    wx.setStorageSync(this._storageKeyname, data)
  }
}
export{CartModel}
export default{
  Apiurl:'http://tp/api/v1/',
  gettoken:'token/user',//获取token
  checktoken: 'token/check',//token检测
  banner:'banner/',//轮播图
  thems:'getthemes',//获取主题
  newproducts:'getnewproducts/',//最新商品
  productdetail:'producty/',//商品详情
  categorys: 'getcategory',//获取分类
  getproducty: 'getproducty/',//根据分类id获取商品
  addaddress:'addressedit',//新增、修改地址
  getaddress:'getaddress',//获取地址
  createorder:'createorder',//创建订单
  pay:'pay/prepay',//支付
  orderdetail:'orderdetail/',//订单快照
  getbrieforder:'getbrieforder',//我的订单
  switchpay:false,//是否开启支付
}
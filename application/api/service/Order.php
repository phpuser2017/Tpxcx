<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/15
 * Time: 11:50
 */

namespace app\api\service;


use app\api\model\Product;
use app\api\model\UserAddress;
use app\exception\OrderException;
use app\exception\UserException;

class Order
{
    //客户端传入的参数
    protected $inproducts;
    //从数据库获取的商品参数
    protected $dataproducts;
    //用户uid
    protected $uid;
    //库存检测
    public function CheckProductStock($uid,$inproducts){
        //客户端传入的值与数据库获取到的值进行比较
        $this->inproducts=$inproducts;
        $this->uid=$uid;
        //按照传递的商品id查询商品信息
        $this->dataproducts=$this->getDataProducts($inproducts);
        //验证订单商品库存
        $orderState=$this->orderStockState();
        if(!$orderState['pass']){
            //库存检测未通过
            $orderState['order_id']=-1;
            return $orderState;
        }
        //库存检测通过创建订单
        $orderSnap=$this->snapOrder($orderState);
    }
    //根据参数查询数据库对应商品信息
    public function getDataProducts($inproducts){
        //将参数数组遍历提取商品id
        $pids=[];
        foreach ($inproducts as $value){
            array_push($pids,$value['product_id']);
        }
        //按照商品id查询商品信息
        $products=Product::all($pids)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();//将数据集转化为数组
        return $products;
    }
    //订单中商品库存检测的状态
    private function orderStockState(){
        /*@pass 订单库存检测状态，一组商品中只要有一个商品库存不足则为检测不通过
         *@orderprice 订单总价
         * @allcount 订单商品总数
         *@pdetailArray 订单中所有商品的信息
         *  */
      $states=[
          'pass'=>true,
          'orderprice'=>0,
          'allcount'=>0,
          'pdetailArray'=>[]
      ];
      //客户端传入的值与数据库获取到的值进行比较
        foreach ($this->inproducts as $product){
            //1-传入的商品id、数量数组与由商品id获取的商品库存信息的数组进行对比
            $detailitem=$this->productStockStates($product['product_id'],$product['count'],$this->dataproducts);
            //对每个商品进行库存检测并处理订单状态
            if(!$detailitem['haveStock']){
                $states['pass']=false;
            }
            $states['orderprice']+=$detailitem['totalprice'];//订单总价
            $states['allcount']+=$detailitem['pcount'];//订单中商品总数量
            array_push($states['pdetailArray'],$detailitem);
        }
        return $states;
    }
    /*数据库中商品状态、商品库存状态
     * @$pid 参数中商品id
     * @$count 商品数量
     * @$products 数据库中商品信息
     * */
    private function productStockStates($pid,$pcount,$products){
        $detailitem=[
            'id'=>null,//商品id
            'haveStock'=>false,//是否由库存
            'pcount'=>0,//提交参数中的商品数量
            'name'=>'',//商品名称
            'totalprice'=>0//提交的该商品的总价
        ];
        $pindex=-1;//商品对应的数据库商品信息集中的序号，默认值
        for($i=0;$i<count($products);$i++){
            if($pid==$products[$i]['id']){
                $pindex=$i;
            }
        }
        if($pindex==-1){
            //客户端传递的商品id在数据库中不存在时
            throw  new OrderException([
                'msg'=>'id为'.$pid.'的商品不存在，创建订单失败'
            ]);
        }else{
            //商品正常存在进行库存检测
            $product=$products[$pindex];
            //组合商品详情
            $detailitem['id']=$product['id'];
            if($product['stock']-$pcount>=0){
                $detailitem['haveStock']=true;
            }
            $detailitem['pcount']=$pcount;
            $detailitem['name']=$product['name'];
            $detailitem['totalprice']=$product['price']*$pcount;
        }
        return $detailitem;
    }
    /*创建订单快照
     *@$orderState 订单状态(订单库存检测状态，订单总价，订单商品总数，订单中所有商品的信息)
     * */
    private function snapOrder($orderState){
        $snap=[
            'orderPrice'=>0,//订单总价格
            'totalCount'=>0,//订单总数量
            'pstates'=>[],//订单中每个商品的状态
            'snapAddress'=>null,//订单收获地址
            'snapShowname'=>'',//订单概括显示的图片
            'snapShowimg'=>''//订单概括显示的数量
        ];
        $snap['orderPrice']=$orderState['orderprice'];
        $snap['totalCount']=$orderState['allcount'];
        $snap['pstates']=$orderState['pdetailArray'];
        $snap['snapAddress']=json_encode($this->getUserAddress());
        $snap['snapShowname']=$this->dataproducts[0]['name'];
        $snap['snapShowimg']=$this->dataproducts[0]['main_img_url'];
        
    
    
    }
    /*查找用户地址
     * */
    private function getUserAddress(){
        $address=UserAddress::where('user_id','=',$this->uid)->find();
        if(!$address){
            throw new UserException([
                'msg'=>'用户收货地址不存在，订单创建失败',
                'errorcode'=>60001
            ]);
        }
        return $address->toArray();//将对象转为数组
    }
}
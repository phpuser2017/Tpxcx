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
use app\api\model\Order as OrderModel;
use app\api\model\OrderProduct as OrderProductModel;
use think\Db;
use think\Exception;

class Order
{
    //客户端传入的参数
    protected $inproducts;
    //从数据库获取的商品参数
    protected $dataproducts;
    //用户uid
    protected $uid;
    /**
     * 库存检测
     * */
    public function CheckProductStock($uid,$inproducts){
        //客户端传入的值与数据库获取到的值进行比较
        $this->inproducts=$inproducts;
        $this->uid=$uid;
        //1、按照传递的商品id查询商品信息
        $this->dataproducts=$this->getDataProducts($inproducts);
        //2、验证订单商品库存
        $orderState=$this->orderStockState();
        if(!$orderState['pass']){
            //库存检测未通过
            $orderState['order_id']=-1;
            return $orderState;
        }
        //3、库存检测通过创建订单
        //3-1.创建订单快照
        $orderSnap=$this->snapOrder($orderState);
        //3-2.创建订单
        $orderState=self::CreatOrder($orderSnap);
        $orderState['pass']=true;
        return $orderState;
    }
    public function PayCheckOrderStock($orderid){
        //根据订单id查询用户传入的参数
        $indata=OrderProductModel::where('order_id','=',$orderid)->select();
        $this->inproducts=$indata;
        //根据参数查询数据库数据
        $this->dataproducts=$this->getDataProducts($indata);
        //进行库存查询对比
        $orders_state=$this->orderStockState();
        return $orders_state;
    }
    /**
     * 根据参数查询数据库对应商品信息
     */
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
    /**
     * 订单中商品库存检测的状态
     */
    private function orderStockState(){
        /*@pass 订单库存检测状态，一组商品中只要有一个商品库存不足则为检测不通过
         *@orderprice 订单总价
         *@allcount 订单商品总数
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
            $states['allcount']+=$detailitem['counts'];//订单中商品总数量
            array_push($states['pdetailArray'],$detailitem);
        }
        return $states;
    }
    /**
     * 数据库中商品状态、商品库存状态
     * @$pid 参数中商品id
     * @$count 商品数量
     * @$products 数据库中商品信息
     * */
    private function productStockStates($pid,$pcount,$products){
        $detailitem=[
            'id'=>null,//商品id
            'haveStock'=>false,//是否由库存
            'counts'=>0,//提交参数中的商品数量
            'price'=>0,//商品单价
            'name'=>'',//商品名称
            'main_img_url'=>'',//商品图片
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
            $detailitem['counts']=$pcount;
            $detailitem['price']=$product['price'];
            $detailitem['main_img_url']=$product['main_img_url'];
            $detailitem['name']=$product['name'];
            $detailitem['totalprice']=$product['price']*$pcount;
        }
        return $detailitem;
    }
    /**
     * 创建订单快照
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
        //多个不同商品时显示不同
        if(count($this->dataproducts)>1){
            $snap['snapShowname'].='等';
        }
        return $snap;

    }
    /**
     * 查找用户地址
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
    /**
     * 创建订单
     * */
    private function CreatOrder($snap){
        //使用事务保证数据一致性
        Db::startTrans();
        try{
            //生成订单号
            $orderno=$this::makeOrderno();
            //1
            $ordermodel=new OrderModel();
            //数据表字段赋值
            $ordermodel->user_id=$this->uid;
            $ordermodel->order_no=$orderno;
            $ordermodel->total_price=$snap['orderPrice'];
            $ordermodel->snap_img=$snap['snapShowimg'];
            $ordermodel->snap_name=$snap['snapShowname'];
            $ordermodel->total_count=$snap['totalCount'];
            $ordermodel->snap_items=json_encode($snap['pstates']);//数组序列化
            $ordermodel->snap_address=$snap['snapAddress'];
            //订单保存到表中
            $ordermodel->save();
            //订单-产品中间表数据添加
            $orderId=$ordermodel->id;
            $ordercreatetime=$ordermodel->create_time;
            //将orderid加入客户端传入的数组中
            foreach ($this->inproducts as &$pro){
                $pro['order_id']=$orderId;
            }
            //2
            $orderproduct=new OrderProductModel();
            //订单-产品中间表数据保存
            $orderproduct->saveAll($this->inproducts);
            Db::commit();
            return [
                'order_no'=>$orderno,
                'order_id'=>$orderId,
                'create_time'=>$ordercreatetime
            ];
        }catch (Exception $ex){
            Db::rollback();
            throw $ex;
        }
    }
    /**
     * 生成订单号(唯一、不重复)
     * 当前年-2018对应的字母.当前月16进制的大写.当前天.当前时间戳后5位.当前微秒时间戳第2位开始取5个.两位0-99的随机数
     * intval() 用于获取变量的整数值
     * strtoupper() 把字符串转换为大写
     * dechex() 把十进制转换为十六进制。
     * substr() 返回字符串的一部分。如果 start 参数是负数且 length 小于或等于 start，则 length 为 0
     * start必需。规定在字符串的何处开始。
     *      正数 - 在字符串的指定位置开始;
     *      负数 - 在从字符串结尾开始的指定位置开始;
     *      0 - 在字符串中的第一个字符处开始
     * length可选。规定被返回字符串的长度。
     *      默认是直到字符串的结尾。
     *      正数 - 从 start 参数所在的位置返回的长度
     *      负数 - 从字符串末端返回的长度
     * sprintf() 把格式化的字符串写入变量中
     *  %02d 在%符号处，插入最小宽度为2的包含正负号的十进制数（负数、0、正数）
     */
    public static function makeOrderno(){
        $yearCode=['A','B','C','D','E','F','G','H','J'];
        $orderno=$yearCode[intval(date('Y'))-2018].strtoupper(dechex(date('m'))).date('d').substr(time(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $orderno;
    }
}
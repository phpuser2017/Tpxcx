<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/12
 * Time: 15:41
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Order as OrderService;
use app\api\service\UserToken as Tokenservice;
use app\api\validate\Idvaliadet;
use app\api\validate\OrderParamCheck;
use app\api\validate\PageParamValidate;
use app\api\model\Order as OrderModel;
use app\exception\OrderException;
use app\exception\SuccessMsg;

class Order extends BaseController
{
    //只要用户可以创建订单
    protected $beforeActionList=[
        'NeedUser'=>['only'=>'CreateOrder'],
        'CheckBaseScope'=>['only'=>'getBriefOrders,OrderDetails']
    ];
    //创建订单
    public function CreateOrder(){
        (new OrderParamCheck())->goCheck();
        //用户选择商品，将选择的商品信息提交到订单api--参数：商品id、商品数量
        $products=input('post.products/a');//获取数组加/a
        //获取用户uid
        $uid=Tokenservice::getCurrentUid();
        //接收到选择的商品信息检查提交的商品的库存量
        //​	有库存
        //​		将订单数据存入数据库，下单成功
        $order=new OrderService();
        $states=$order->CheckProductStock($uid,$products);
        return json($states);
        //​		再次检测库存，库存正常则调用微信支付 接口 Pay
        //​		在调用微信支付至微信支付返回结果间再次检测库存
        //      根据微信支付结果对库存量进行操作
        //​		成功：减少库存，
        //​	无库存
    }
    /**
     * 我的订单[管理员、用户都可以访问，基本权限]
     * @page 页数
     * @len 每页显示几条数据
     * */
    public function getBriefOrders($page=1,$len=15){
        (new PageParamValidate())->goCheck();
        //获取用户id
        $uid=Tokenservice::getCurrentUid();
        //查询用户订单快照
        $paginateorders=OrderModel::getSnapOrder($uid,$page,$len);
        if($paginateorders->isEmpty()){
            return json([
                'data'=>[],
                'nowpage'=>$paginateorders->getCurrentPage()
            ]);
        }
        $ordresdata=$paginateorders->hidden(['snap_items','snap_address','prepay_id'])->toArray();//对象转数组
        return json([
            'data'=>$ordresdata,
            'nowpage'=>$paginateorders->getCurrentPage()
        ]);
    }
    /**
     * 订单详情
     *
     * */
    public function OrderDetails($id){
        (new Idvaliadet())->goCheck();
        $orderdata=OrderModel::get($id);
        if(!$orderdata){
            throw new OrderException();
        }else{
            return json($orderdata->hidden(['prepay_id']));
        }
    }
    /**
     * cms获取全部订单简要信息（分页）
     * @page 页数
     * @len 每页显示几条数据
     */
    public function getOrder($page=1, $len = 20){
        (new PageParamValidate())->goCheck();
        $pagdata = OrderModel::getOrderByPage($page, $len);
        if ($pagdata->isEmpty())
        {
            return json([
                'nowpage' => $pagdata->getCurrentPage(),
                'data' => []
            ]);
        }
        $data = $pagdata->hidden(['snap_items', 'snap_address'])->toArray();
        return json([
            'nowpage' => $pagdata->getCurrentPage(),
            'data' => $data
        ]);
    }
    /**
     * 发货发送微信模板消息
     * @ id 订单号（id）
     */
    public function SendShopSendMsg($id){
        (new Idvaliadet())->goCheck();
        $order=new OrderService();
        $success=$order->SendShop($id);
        if($success){
            return new SuccessMsg();
        }
    }
}
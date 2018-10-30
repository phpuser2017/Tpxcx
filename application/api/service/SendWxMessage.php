<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 16:33
 */

namespace app\api\service;


use app\api\model\User;
use app\exception\OrderException;
use app\exception\UserException;

class SendWxMessage extends WxMessage
{
    public function SendInfo($order,$JumpPath=''){
        if(!$order){
            throw new OrderException();
        }
        $this->templateId=config('wxconfig.MsgId');
        $this->page=$JumpPath;
        $this->formId=$order->prepay_id;
        $this->preMessageData($order);
        $this->emphasisKeyword='keyword2.DATA';
        //调用父类发送消息的方法
        return parent::sendMessage($this->getOrderUserOpenID($order->user_id));
    }
    //组装模板显示数据
    private function preMessageData($order)
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '顺风速运',
            ],
            'keyword2' => [
                'value' => $order->snap_name,
                'color' => '#27408B'
            ],
            'keyword3' => [
                'value' => $order->order_no
            ],
            'keyword4' => [
                'value' => $dt->format("Y-m-d H:i")
            ]
        ];
        $this->data = $data;
    }
    //获取订单所属用户的open_id
    private function getOrderUserOpenID($uid)
    {
        $user = User::get($uid);
        if (!$user) {
            throw new UserException();
        }
        return $user->openid;
    }
}
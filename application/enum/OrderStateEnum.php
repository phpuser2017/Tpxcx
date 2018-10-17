<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/17
 * Time: 10:27
 */

namespace app\enum;


class OrderStateEnum
{
    const Unpaid=1;//待支付
    const Paid=2;//已支付
    const Delivered=3;//支付已发货
    const PaidNoStock=4;//支付，库存不足
}
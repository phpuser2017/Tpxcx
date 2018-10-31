<?php
/**
 * Created by PhpStorm.
 * User: fan
 * Date: 2018/10/30
 * Time: 16:36
 */

namespace app\api\service;


use think\Exception;

class WxMessage
{
    private $sendurl;
    private $touser;//接收者（用户）的 openid
    private $color='black';

    protected $templateId;//所需下发的模板消息的id
    protected $page;//点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转
    protected $formId;//表单提交场景下，为 submit 事件带上的 formId；支付场景下，为本次支付的 prepay_id
    protected $data;//模板内容，不填则下发空模板
    protected $emphasisKeyword;//模板需要放大的关键词，不填则默认无放大

    function __construct()
    {
        $this->sendurl=config('wxconfig.sendUrl');
        $accesstoken=new AccessToken();
        $token=$accesstoken->get();
        $this->sendurl=sprintf($this->sendurl,$token);
    }
    // 开发工具中拉起的微信支付prepay_id是无效的，需要在真机上拉起支付
    protected function sendMessage($userID)
    {
        $data = [
            'touser' => $userID,
            'template_id' => $this->templateId,
            'page' => $this->page,
            'form_id' => $this->formId,
            'data' => $this->data,
            'color' => $this->color,
            'emphasis_keyword' => $this->emphasisKeyword
        ];
        $result = curl_post($this->sendurl, json_encode($data),'');
        $result = json_decode($result, true);
        if ($result['errcode'] == 0) {
            return true;
        } else {
            throw new Exception('模板消息发送失败,  ' . $result['errmsg']);
        }
    }
}
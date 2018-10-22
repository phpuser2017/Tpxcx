<?php
return [
    //设置图片域名'tp/static/images'
    'img_pre'=>((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://'.$_SERVER['SERVER_NAME'].'/static/images',
    //token缓存有效期
    'token_vld'=>7200
];
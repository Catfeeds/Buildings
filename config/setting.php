<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 七牛管理
    |--------------------------------------------------------------------------
    */
    // 七牛
    'qiniu_access_key' => 'c_M1yo7k90djYAgDst93NM3hLOz1XqYIKYhaNJZ4', // 七牛访问KEY
    'qiniu_secret_key' => 'Gb2K_HZbepbu-A45y646sP1NNZF3AqzY_w680d5h', // 七牛访问秘钥

    // 开发 七牛存储空间
    'qiniu_bucket' => env('QINIU_BUCKET', 'louwang-test'),
    // 七牛访问url
    'qiniu_url' => env('QINIU_URL', 'http://osibaji20.bkt.clouddn.com/'),
    // 七牛测试后缀
    'qiniu_suffix' => '-test',

    'version' => '?version=20180531',
];
<?php

return [
    'ossServer' => env('ALIOSS_SERVER', 'https://oss-cn-shenzhen.aliyuncs.com'),                      // 外网
    'ossServerInternal' => env('ALIOSS_SERVERINTERNAL', 'https://oss-cn-shenzhen-internal.aliyuncs.com'),      // 内网
    'accessKeyId' => env('ALIOSS_KEYID', ''),                     // key
    'accessKeySecret' => env('ALIOSS_KEYSECRET', ''),             // secret
    'bucketName' => env('ALIOSS_BUCKETNAME', 'tesmall'),              // bucket,
    'ossHost'   =>  env('ALIOSS_HOST', 'https://tesmall.oss-cn-shenzhen.aliyuncs.com'),
    'prefix'   =>  env('ALIOSS_PREFIX', '')                     //上传文件的前缀
];

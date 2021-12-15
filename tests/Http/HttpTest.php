<?php

namespace Sakurairo\Tests\Http;

use GuzzleHttp\Client;

require __DIR__.'/../../vendor/autoload.php';

$client = new Client([
    'verify' => true,
    'timeout' => 15,
    'http_errors' => false,
]);

//传入一个Client实例或不传
$res = http($client)->get('https://baidu.com')->getBody()->toString();
var_dump($res);
<?php

namespace app\controller;

use app\BaseController;
use think\cache\driver\Redis;

class Index extends BaseController
{
    public function index()
    {
        $redisConfig = config('redis');
        $redis = new Redis($redisConfig);
        $result = redis_queue_send($redis->handler(),'async_one', ['id' => 1, 'name' => 'test']);
        return json($result);
    }

    public function hello($name = 'ThinkPHP8')
    {
        return 'hello,' . $name;
    }
}

<?php

namespace app\service;

use think\facade\Config;
use Workerman\RedisQueue\Client as RedisClient;
use think\facade\Db;
use Workerman\Timer;
use Workerman\Worker;

class Serv00Worker extends Worker
{
    public function __construct($socket_name = '', array $context_option = array())
    {
        parent::__construct($socket_name, $context_option);
        $this->onWorkerStart = array($this, 'onWorkerStart');
        $this->onWorkerStop = array($this, 'onWorkerStop');
    }

    public function onWorkerStart(Worker $worker): void
    {
        $this->initMySqlConfig();
        //启动定时任务
        echo "hello";
        $cronTabs = [];
        $redisClient = $this->initRedisClient(Config::get('redis'));
        $this->AsyncOne($redisClient);
    }




    private function AsyncOne(RedisClient $redis): void
    {
        $redis->subscribe("async_one", function ($data) {
            // todo:: 通了
            var_dump($data);
        });
    }


    private function initRedisClient($redisConfig): RedisClient
    {
        return new RedisClient("redis://${redisConfig['host']}:${redisConfig['port']}", [
            'auth' => $redisConfig['password'],
            'db' => $redisConfig['select']
        ]);
    }


    public function initMySqlConfig(): void
    {
        // 初始化数据库配置
        $dbConfig = Config::get('database');
        Db::setConfig($dbConfig);
        // 定时请求mysql，防止mysql长时间不使用被断开
        \Workerman\Timer::add(55, function () {
            Db::query("select 1 limit 1");
        });
    }


    public function onWorkerStop(): void
    {
        //停止定时任务
        Timer::delAll();
    }


}
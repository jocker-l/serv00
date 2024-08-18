<?php
// 应用公共文件


function redis_queue_send($redis, $queue, $data, $delay = 0)
{
    $queue_waiting = '{redis-queue}-waiting'; //1.0.5版本之前为redis-queue-waiting
    $queue_delay = '{redis-queue}-delayed';//1.0.5版本之前为redis-queue-delayed

    $now = time();
    $package_str = json_encode([
        'id' => rand(),
        'time' => $now,
        'delay' => 0,
        'attempts' => 0,
        'queue' => $queue,
        'data' => $data
    ]);
    if ($delay) {
        return $redis->zAdd($queue_delay, $now + $delay, $package_str);
    }
    return $redis->lPush($queue_waiting . $queue, $package_str);
}
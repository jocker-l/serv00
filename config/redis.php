<?php


return [
   'host'=> env('REDIS_HOST', 's6.serv00.com'),
   'port'=> env('REDIS_PORT', 62294),
   'password'=> env('REDIS_PASSWORD', 'password'),
   'select'=> env('REDIS_DATABASE', 0),
];
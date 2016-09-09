<?php


return [


    /**
     * true: disable all request, except the $allowIp ips
     * false: allow all request from any ip
     */

    'ipBlock' => false,

    /**
     * Serve the packages only for this ip-s
     */

    'allowIp' => ['127.0.0.1'],

];
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Symfony\Component\HttpFoundation\Request;

class TrustProxies extends Middleware
{
    /**
     * Các proxy tin cậy (nếu không có thì để null).
     */
    protected $proxies = '*'; // hoặc null nếu bạn không dùng proxy

    /**
     * Header xác định địa chỉ IP gốc.
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | 
                         Request::HEADER_X_FORWARDED_HOST | 
                         Request::HEADER_X_FORWARDED_PORT | 
                         Request::HEADER_X_FORWARDED_PROTO;
}

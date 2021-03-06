<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Utils\Context;

/**
 * RequestMiddleware
 * 接到客户端请求，通过该中间件进行一些调整
 * @package App\Middleware
 * User：YM
 * Date：2019/12/16
 * Time：上午12:13
 */
class RequestMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function __construct(ContainerInterface $container,ServerRequestInterface $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 为每一个请求增加一个qid
        // $request = Context::override(ServerRequestInterface::class, function (ServerRequestInterface $request) {
        //     $request = $request->withAddedHeader('qid', 111);
        //     return $request;
        // });


        // 利用协程上下文存储请求开始的时间，用来计算程序执行时间
        Context::set('request_start_time', microtime(true));

        return $handler->handle($request);
    }
}
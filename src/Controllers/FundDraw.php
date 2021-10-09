<?php

namespace Chen\NbdomainLogin\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Chen\NbdomainLogin\Command\FundManager;

class FundDraw implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        FundManager::fundDraw();
        return new HtmlResponse('ok');
    }
}
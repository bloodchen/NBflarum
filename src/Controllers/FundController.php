<?php

namespace Chen\NbdomainLogin\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Chen\NbdomainLogin\Command\FundManager;

class FundController implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        //$string = file_get_contents("../packages/nbflarum.json");
        //$json_a = json_decode($string);
        $address = FundManager::getAddress();
        return new HtmlResponse('{"address":"'.$address.'"}');
    }
}
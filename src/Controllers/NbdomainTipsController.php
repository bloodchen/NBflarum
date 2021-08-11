<?php

namespace Chen\NbdomainLogin\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Chen\NbdomainLogin\Nbdomaintips;
use Chen\NbdomainLogin\Command\CreateNbdomaintips;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
//use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Support\Arr;
use Flarum\Http\UrlGenerator;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;

class NbdomainTipsController implements RequestHandlerInterface
{
    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    //public $serializer = 'Chen\NbdomainLogin\Api\Serializer\NbdomaintipsSerializer';

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }
     * @param ResponseFactory             $response
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator                $url
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url, Dispatcher $bus)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url = $url;
        $this->bus = $bus;
    }

    public function handle(Request $request): Response
    {
		$session     = $request->getAttribute('session');
		$queryParams = $request->getQueryParams();
		$post_id = Arr::get($queryParams, 'post_id');
		$user_id = Arr::get($queryParams, 'user_id');
		$sender_id = Arr::get($queryParams, 'sender_id');
		$tips_amount = Arr::get($queryParams, 'tips_amount');
		$trx = Arr::get($queryParams, 'trx');
		$date = date("Y-m-d H:i:s");
		//print_r($queryParams); 
		$data = array('user_id' => $user_id, 'post_id' => $post_id, 'sender_id' => $sender_id, 'tips_amount' => $tips_amount, 'trx' => $trx, 'date' => $date);
		//print_r($data);
		//exit;
        $this->bus->dispatch(
            new CreateNbdomaintips($request->getAttribute('actor'), $data)
        );
		
		
		return new HtmlResponse('
<html>
  <head>
  </head>
  <body >
  </body>
</html>
		');
		
    }


}

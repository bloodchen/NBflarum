<?php

namespace Chen\NbdomainLogin\Controllers;

use Flarum\Api\Controller\AbstractCreateController;
use Chen\NbdomainLogin\Command\CreateNbdomaintips;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateNbdomaintipsController implements AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = 'Chen\NbdomainLogin\Api\Serializer\NbdomaintipsSerializer';

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document               $document
     *
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(
            new CreateNbdomaintips($request->getAttribute('actor'), Arr::get($request->getParsedBody(), 'data.attributes', $request->getParsedBody()))
        );
    }
/*
    public function handle(Request $request): Response
    {
		$session     = $request->getAttribute('session');
		$queryParams = $request->getQueryParams();
		$post_id = Arr::get($queryParams, 'post_id');
		$user_id = Arr::get($queryParams, 'user_id');
		$sender_id = Arr::get($queryParams, 'sender_id');
		$tips_amount = Arr::get($queryParams, 'tips_amount');
		$trx = Arr::get($queryParams, 'trx');
		print_r($queryParams); exit;
		//exit;
		
		
		return new HtmlResponse('
<html>
  <head>
  </head>
  <body >
  </body>
</html>
		');
    }
*/

}

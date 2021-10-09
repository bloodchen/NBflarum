<?php

namespace Chen\NbdomainLogin\Controllers;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Post\PostRepository;
use Chen\NbdomainLogin\Api\Serializer\PostNbdomaintipsSerializer;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
//use Chen\NbdomainLogin\Nbdomaintips;
use Chen\NbdomainLogin\Command\FundManager;



function mlog(){
        $args = func_get_args();
        foreach ($args as $arg){
            file_put_contents('debug.txt', var_export($arg,true)."\n",FILE_APPEND);
        }
}

class TipsController extends AbstractListController
{
    public $serializer = PostNbdomaintipsSerializer::class;

    public $include = ['nbdomaintips'];

    public $optionalInclude = ['user', 'post'];

    /**
     * @var PostRepository
     */
    protected $posts;

    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document               $document
     *
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $from = date(Arr::get($request->getQueryParams(), 'from'));
        $to = date(Arr::get($request->getQueryParams(), 'to'));
        return FundManager::tipRecordsFromTime($from,$to);
    }

}

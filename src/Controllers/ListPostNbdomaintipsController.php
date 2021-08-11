<?php

namespace Chen\NbdomainLogin\Controllers;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Post\PostRepository;
use Chen\NbdomainLogin\Api\Serializer\PostNbdomaintipsSerializer;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListPostNbdomaintipsController extends AbstractListController
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
        $postId = Arr::get($request->getQueryParams(), 'id');
        $post = $this->posts->findOrFail($postId, $request->getAttribute('actor'));

        return $post->nbdomaintips()->get()->sortByDesc('id');
    }
}

<?php

/*
 * This file is part of chen/nbdomain-login.
 *
 * Copyright (c) 2021 Chen M.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Chen\NbdomainLogin;


use Flarum\Extend;
use Flarum\Frontend\Document;
use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\UserSerializer;
//use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Api\Serializer\PostSerializer;
use Flarum\Database\AbstractModel;
use Flarum\Api\Controller;
//use Flarum\User\Event\Saving;
use Flarum\Post\Event\Saving;
use Flarum\Post\Post;
use Flarum\User\User;
//use Chen\NbdomainLogin\Listener\AddUserOpayAddressAttribute;
/*use Chen\NbdomainLogin\Controllers\NbdomaintipsController;
use Chen\NbdomainLogin\Controllers\NbdomainLoginController;
use Chen\NbdomainLogin\Controllers\ListPostNbdomaintipsController;*/

return [
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $document->head[] = '<script src="https://unpkg.com/opay@1.0.93/opay.min.js"></script>
								 <script src="https://unpkg.com/nblib@latest/nblib.min.js" ></script>
								 <style>td, th { padding: 1px; }</style>
								 ';
								 								 
        }),
    (new Extend\Frontend('forum'))
        ->content(function (Document $document) {
            $document->foot[] = '
<div id="awalpay" style="width:250px;height:200px;"></div>			
<script>			
	//opay.init({ containerID: "awalpay",timeout:50,debug:true });
	//opay.setUI({close:false});	  
	nblib.init({
	  debug:true,
	  opay:opay,
	  enable_write:false
	});
</script>
			';
        }),


    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    (new Extend\Model(Post::class))
        ->relationship('nbdomaintips', function (AbstractModel $model) {
            return $model->hasMany(PostNbdomaintips::class, 'post_id');
                
        }),
		
    new Extend\Locales(__DIR__ . '/resources/locale'),
    (new Extend\Routes('forum'))
        ->get('/nbdomain-login', 'chen.nbdomain-login', Controllers\NbdomainLoginController::class)		
		->get('/nbdomaintips', 'chen.nbdomaintips', Controllers\NbdomainTipsController::class)
		->get('/posts/{id}/nbdomaintipslist', 'chen.nbdomaintipslist', Controllers\ListPostNbdomaintipsController::class),
		
    //(new Extend\Event())
	//	->listen(Serializing::class, AddUserOpayAddressAttribute::class),
		
    (new Extend\ApiSerializer(UserSerializer::class))
        ->attribute('opayaddress', function (UserSerializer $serializer, User $user) {
            return $user->opayaddress;
        }),
	
];

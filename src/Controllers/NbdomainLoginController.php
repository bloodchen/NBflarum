<?php

namespace Chen\NbdomainLogin\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
//use Chen\NbdomainLogin\Providers\NbdomainLoginProvider;
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
use Illuminate\Database\Capsule\Manager as DB;

//$use Illuminate\Support\Facades\DB;

use Flarum\User\LoginProvider;


function mlog(){
        $args = func_get_args();
        foreach ($args as $arg){
            file_put_contents('debug.txt', var_export($arg,true)."\n",FILE_APPEND);
        }
}

class NbdomainLoginController implements RequestHandlerInterface
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

    /**
     * @param ResponseFactory             $response
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator                $url
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url = $url;
    }

    public function handle(Request $request): Response
    {
		$session     = $request->getAttribute('session');
		$queryParams = $request->getQueryParams();

		//$domainid = Arr::get($queryParams, 'domainid');
		$nbdomainname = Arr::get($queryParams, 'userid');
		$hash = Arr::get($queryParams, 'hash');	// nickname
		$address = Arr::get($queryParams, 'address');	// avatar
		$nickname = '';
		
		//$redirectUri = $this->url->to('forum')->route('chen.nbdomain-login').'?userid=';
		if (!empty($nbdomainname)) {
			$c = file_get_contents("https://api.nbdomain.com/api/?nid=_profile.".$nbdomainname);
			$avatarurl = '';
			$json = json_decode($c);
			mlog($c);
			if ($json && $json->code == 0) {
				mlog("22");
				$obj = $json->obj;
				$data = explode(',', $obj->avatar);
				$hash = $obj->nickname;
				$content = base64_decode($data[1]);
				mkdir("assets/avatars");
				$file = fopen("assets/avatars/".str_replace('.','',$nbdomainname).".jpg", "wb");
				$avatarurl = str_replace('.','',$nbdomainname).".jpg";
				if($file){
					fwrite($file, $content);
					fclose($file);
				}
			}
			if ($hash <> '') $nickname = $hash; 
			else $nickname = str_replace('.','',$nbdomainname);
			$user = array('username' => $nickname, 'email' => 'flarum@'.$nbdomainname);  
			$existuser = LoginProvider::logIn('nbdomainlogin', str_replace('.','',$nbdomainname));
			$dirty = false;
			if (!$existuser) {
				$existuser = User::register($nickname, 'flarum@'.$nbdomainname, '$2y$10$L6u9FX8zwy5d.1BL8vopK.ya7iKQvR./KIZ2LY6tnWBtvr6ROkMvW');
				$existuser->opayaddress = $address;
				$existuser->activate();
				if ($avatarurl != '') {
					$existuser->changeAvatarPath($avatarurl);
				}
				$existuser->save();	
			} else {
				
			}
			$count = USER::count();
			if($count<3){ //set first 3 users as admin
				$existuser->groups()->sync([1]); 
				$dirty = true;
			}
			if($dirty)
				$existuser->save();
			//exit;				
				return $this->response->make(
						'nbdomainlogin',
						str_replace('.','',$nbdomainname),
						function (Registration $registration) use ($user, $nbdomainname, $nickname, $avatarurl) {
								$registration
									->provideTrustedEmail('flarum@'.$nbdomainname)
									->provide('username', $nickname)
									->setPayload($user);
							
						}
					);

		}	
		
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

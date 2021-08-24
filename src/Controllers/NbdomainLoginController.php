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

define("NBAPI","http://127.0.0.1:9000/api/");

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
		if(!isset($_COOKIE['__dAC-C'])){
			return new HtmlResponse('');
		}
		setcookie('__dAC-C', null, 1, '/');
		$nbdomainname = Arr::get($queryParams, 'userid');
		$data = Arr::get($queryParams, 'data');	// nickname
		$sig = Arr::get($queryParams, 'sig');
		$address = Arr::get($queryParams, 'address');	// avatar
		$nickname = '';

		/*$url = NBAPI."util/verify?domain=".$nbdomainname."&sig=".$sig."&data=".$data;
		$r = file_get_contents($url);
		mlog($r);
		if($r&&json_decode($r)->code!=0){ //verification failed
			return new HtmlResponse('');
		}*/
		if (!empty($nbdomainname)) {
			$c = file_get_contents(NBAPI."?nid=_profile.".$nbdomainname);
			$avatarurl = '';
			$json = json_decode($c);
			//mlog($c);
			if ($json && $json->code == 0 && $json->obj->avatar) {
				//mlog("22");
				$obj = $json->obj;
				$data = explode(',', $obj->avatar);
				$nickname = $obj->nickname;
				$content = base64_decode($data[1]);
				mkdir("assets/avatars");
				$file = fopen("assets/avatars/".str_replace('.','',$nbdomainname).".jpg", "wb");
				$avatarurl = str_replace('.','',$nbdomainname).".jpg";
				if($file){
					fwrite($file, $content);
					fclose($file);
				}
			}
			if ($nickname == '')  
			{
				$pair = explode(".",$nbdomainname);
				if($pair[1]=="b") $nickname = $pair[0];
				else $nickname = $pair[0].$pair[1];
			}
			$user = array('username' => $nickname, 'email' => 'flarum@'.$nbdomainname);  
			$existuser = LoginProvider::logIn('nbdomainlogin', str_replace('.','',$nbdomainname));
			$dirty = false;
			if (!$existuser) {
				//mlog("1");
				$existuser = User::register($nickname, 'flarum@'.$nbdomainname, '12345678poi');
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

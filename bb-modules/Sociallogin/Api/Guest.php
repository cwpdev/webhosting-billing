<?php
/**
* CWP Social login for BillingBox
* Cecilio Morales - cecilio.dev@gmail.com
 */

namespace Box\Mod\Sociallogin\Api;

class Guest extends \Api_Abstract
{
    public function get_config(){ 
        //print_r(['DHHDH', 'PERRO']);
        $extensionService = $this->di['mod_service']('extension');
        $goole_login_settings = $extensionService->getConfig('mod_sociallogin');
        if($goole_login_settings['sing_in_google_enable'] == 1){
            $googleLogin = $this->di['google_login'];
            $googleLogin->setClientId($goole_login_settings['google_client_id']);
            $googleLogin->setClientSecret($goole_login_settings['google_client_secret']);
            $googleLogin->setRedirectUri($this->getService()->getGoogleRedirect());
            $googleLogin->addScope("email");
            $googleLogin->addScope("profile");
            $this->getService()->setRedirectAfterSocial();
            return [
                'sing_in_google_enable' => true,
                'auth_url' => $googleLogin->createAuthUrl()
            ];
            //print_r($goole_login_settings); die('JULIO');
        }
        return ['sing_in_google_enable' => false];
    }
    public function login_with_google(){
        $extensionService = $this->di['mod_service']('extension');
        $goole_login_settings = $extensionService->getConfig('mod_sociallogin');
        $googleLogin = $this->di['google_login'];
        $googleLogin->setClientId($goole_login_settings['google_client_id']);
        $googleLogin->setClientSecret($goole_login_settings['google_client_secret']);
        $googleLogin->setRedirectUri($this->getService()->getGoogleRedirect());
        $googleLogin->addScope("email");
        $googleLogin->addScope("profile");

        
        $token = $googleLogin->fetchAccessTokenWithAuthCode($this->di['request']->getQuery('code'));
        //$token = $googleLogin->fetchAccessTokenWithAuthCode("ssssss");
        if(!is_array($token) || !array_key_exists('access_token', $token)){
            throw new \Box_Exception('Invalid google token');
        } 
        $googleLogin->setAccessToken($token['access_token']);
        
        // get profile info
        //$google_oauth = new Google_Service_Oauth2($client);
        $google_oauth = $this->di['google_oauth']($googleLogin);
        $google_account_info = $google_oauth->userinfo->get();
        $email =  $google_account_info->email;
        $name =  $google_account_info->name;
        $service = $this->getService();
        $event_params['ip'] = $this->ip;
        $event_params['email'] = $email;

        $this->di['events_manager']->fire(array('event'=>'onBeforeClientLogin', 'params'=>$event_params));
        $client = $service->authorizeSocialClient($email, $name);
        
        $this->di['events_manager']->fire(array('event'=>'onAfterClientLogin', 'params'=>array('id'=>$client->id, 'ip'=>$this->ip))); 
        $this->di['session']->set('client_id', $client->id);
        $this->di['logger']->info('Client #%s logged in', $client->id);
        return $this->getService()->getRedirectAfterSocial();;
    }
} 
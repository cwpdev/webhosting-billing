<?php
/**
* CWP Social login for BillingBox
* Cecilio Morales - cecilio.dev@gmail.com
 */

namespace Box\Mod\Sociallogin;

use Box\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    protected $di;
    protected $redirect_route = 'sociallogin/google/';
    /**
     * @param \Box_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Box_Di
     */
    public function getDi()
    {
        return $this->di;
    }
    public function setRedirectAfterSocial(){
        $this->di['session']->set('redirect_after_social', $this->di['request']->getServer('REQUEST_URI'));
    }
    public function getRedirectAfterSocial(){
        $redirect_route = $this->di['session']->get('redirect_after_social');
        return !empty($redirect_route) ? $redirect_route : '/';
    }
    public function getGoogleRedirect(){
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] .'/' . $this->redirect_route;
    }
    public function authorizeSocialClient($email, $name){
        //$model = $this->di['db']->findOne('Client', 'email = ? AND status = ?', array($email, \Model_Client::ACTIVE));
        $client = $this->di['db']->findOne('Client', 'email = ?', [$email]);
        if ($client == null) {
            $client = $this->di['db']->dispense('Client');
            $client->email      = strtolower($email);
            $client->first_name = ucwords($name);
            $client->pass       = $this->di['password']->hashIt(bin2hex(random_bytes(20)));
            $client->role       = 'client';
            $client->created_at = date('Y-m-d H:i:s');
            $client->updated_at = date('Y-m-d H:i:s');
            $this->di['db']->store($client);
        }
        return $client;
        
    }
}
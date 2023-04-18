<?php
/**
* CWP Social login for BillingBox
* Cecilio Morales - cecilio.dev@gmail.com
 */


namespace Box\Mod\Sociallogin\Controller;

class Client implements \Box\InjectionAwareInterface
{
    protected $di;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    public function register(\Box_App &$app)
    {
        
        $app->get('/sociallogin/google/', 'google_login', array(), get_class($this));
    }
    public function google_login(\Box_App $app)
    {
        $api = $this->di['api_guest'];
        $redirect = $api->sociallogin_login_with_google();
        return $app->redirect($redirect);
    }
}
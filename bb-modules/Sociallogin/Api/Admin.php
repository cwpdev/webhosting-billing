<?php
/**
* CWP Social login for BillingBox
* Cecilio Morales - cecilio.dev@gmail.com
 */


namespace Box\Mod\Sociallogin\Api;

class Admin extends \Api_Abstract
{
    public function get_google_redirect(){
        die('LELELEL');
        return 'llll';
        return $this->getService()->getGoogleRedirect();
    }
}
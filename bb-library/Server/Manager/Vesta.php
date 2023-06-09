<?php
/**
 * BoxBilling
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file LICENSE.txt
 * It is also available through the world-wide-web at this URL:
 * http://www.boxbilling.com/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@boxbilling.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2012 BoxBilling (http://www.boxbilling.com)
 * @license   http://www.boxbilling.com/LICENSE.txt
 * @version   $Id$
 */
class Server_Manager_Vesta extends Server_Manager
{
    /**
     * Method is called just after obejct contruct is complete.
     * Add required parameters checks here.
     */
    public function init()
    {

    }
    /**
     * Return server manager parameters.
     * @return type
     */
    public static function getForm()
    {
        return array(
            'label'     =>  'Vesta Server Manager V2',
        );
    }
    /**
     * Returns link to account management page
     *
     * @return string
     */
    public function getLoginUrl()
    {
        $host = 'http';
        if ($this->_config['secure']) {
            $host .= 's';
        }
        $host .= '://' . $this->_config['host'] . ':'.$this->_config['port'].'/';
        return $host;
    }
    /**
     * Returns link to reseller account management
     * @return string
     */
    public function getResellerLoginUrl()
    {
        return 'http://www.google.com?q=whm';
    }

    private function _makeRequest($params)
    {
        $host = 'http';
        if ($this->_config['secure']) {
            $host .= 's';
        }
        $host .= '://' . $this->_config['host'] . ':'.$this->_config['port'].'/api/';


// Server credentials
        $params['user'] = $this->_config['username'];
        $params['password'] = $this->_config['password'];


// Send POST query via cURL
        $postdata = http_build_query($params);
        $curl = curl_init();
        $timeout = 5;
        curl_setopt($curl, CURLOPT_URL, $host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($curl);
        curl_close($curl);
        if(strpos($result, 'Error')!== false){
            throw new Server_Exception('Connection to server failed  '.$result);
        }

        return $result;
    }
    private function _getPackageName(Server_Package $package)
    {
        $name = $package->getName();

        return $name;
    }
    /**
     * This method is called to check if configuration is correct
     * and class can connect to server
     *
     * @return boolean
     */
    public function testConnection()
    {



        // Server credentials
        $vst_command = 'v-check-user-password';
        $vst_returncode = 'yes';
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $this->_config['username'],
            'arg2' => $this->_config['password'],
        );

// Make request and check sys info
        $result = $this->_makeRequest($postvars);
        if(strpos($result, 'Error')!== false){
            throw new Server_Exception('Connection to server failed  '.$result);
        }
        else {
            if ($result == 0) {
                return true;
            } else {
                throw new Server_Exception('Connection to server failed '.$result);
            }
        }
        return true;
    }
    /**
     * MEthods retrieves information from server, assignes new values to
     * cloned Server_Account object and returns it.
     * @param Server_Account $a
     * @return Server_Account
     */
    public function synchronizeAccount(Server_Account $a)
    {
        $this->getLog()->info('Synchronizing account with server '.$a->getUsername());
        $new = clone $a;
        //@example - retrieve username from server and set it to cloned object
        //$new->setUsername('newusername');
        return $new;
    }
    /**
     * Create new account on server
     *
     * @param Server_Account $a
     */
    public function createAccount(Server_Account $a)
    {

        $p = $a->getPackage();
        $packname = $this->_getPackageName($p);


        $client = $a->getClient();
        // Server credentials
        $vst_command = 'v-add-user';
        $vst_returncode = 'yes';
        $parts = explode(" ", $client->getFullName());
        $lastname = array_pop($parts);
        $firstname = implode(" ", $parts);
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $a->getUsername(),
            'arg2' => $a->getPassword(),
            'arg3' => $client->getEmail(),
            'arg4' => $packname,
            'arg5' => $firstname,
            'arg6' => $lastname
        );
// Make request and create user
        $result = $this->_makeRequest($postvars);
        if($result == 0)
        {

// Create Domain Prepare POST query
            $postvars2 = array(

                'returncode' => 'yes',
                'cmd' => 'v-add-domain',
                'arg1' => $a->getUsername(),
                'arg2' => $a->getDomain()
            );
            $result2 = $this->_makeRequest($postvars2);
        }
        else {
        }
        if($result2 != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: Create Domain failure '.$result2);
        }
        return true;
    }
    /**
     * Suspend account on server
     * @param Server_Account $a
     */
    public function suspendAccount(Server_Account $a)
    {
        $user = $a->getUsername();
// Prepare POST query
        $postvars = array(
            'returncode' => 'yes',
            'cmd' => 'v-suspend-user',
            'arg1' => $a->getUsername(),
            'arg2' => 'no'
        );
// Make request and suspend user
        $result = $this->_makeRequest($postvars);
// Check if error 6 the account is suspended on server
        if($result == '6'){
            return true;
        }
        if($result != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: Suspend Account Error '.$result.$suspended);
        }
        return true;

    }
    /**
     * Unsuspend account on server
     * @param Server_Account $a
     */
    public function unsuspendAccount(Server_Account $a)
    {

        // Server credentials
        $vst_command = 'v-unsuspend-user';
        $vst_returncode = 'yes';
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $a->getUsername(),
            'arg2' => 'no',
            'arg3' =>'',
            'arg4' =>'',
            'arg5' =>'',
            'arg6' =>'',
            'arg7' =>'',
            'arg8' =>'',
            'arg9' =>''

        );

        $result = $this->_makeRequest($postvars);
        if($result != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: unSuspend Account Error '.$result);
        }
        return true;
    }

    /**
     * Cancel account on server
     * @param Server_Account $a
     */
    public function cancelAccount(Server_Account $a)
    {


        // Server credentials
        $vst_username = $this->_config['username'];
        $vst_password = $this->_config['password'];
        $vst_command = 'v-delete-user';
        $vst_returncode = 'yes';
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $a->getUsername(),
            'arg2' => 'no'

        );
// Make request and delete user
        $result = $this->_makeRequest($postvars);
        if($result == '3'){
            return true;
        }
        else {if($result != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: Cancel Account Error '.$result);
        }}
        return true;
    }
    /**
     * Change account package on server
     * @param Server_Account $a
     * @param Server_Package $p
     */
    public function changeAccountPackage(Server_Account $a, Server_Package $p)
    {

        $package = $a->getPackage()->getName();


// Server credentials
        $vst_username = $this->_config['username'];
        $vst_password = $this->_config['password'];
        $vst_command = 'v-change-user-package';
        $vst_returncode = 'yes';
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $a->getUsername(),
            'arg2' => $this->_getPackageName($p),
            'arg3' => 'no'

        );
// Make request and change package
        $result = $this->_makeRequest($postvars);
        if($result != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: Change User package Account Error '.$result);
        }
        return true;
    }
    /**
     * Change account username on server
     * @param Server_Account $a
     * @param type $new - new account username
     */
    public function changeAccountUsername(Server_Account $a, $new)
    {
        {
            throw new Server_Exception('Server Manager Vesta CP Error: Not Supported');
        }
    }
    /**
     * Change account domain on server
     * @param Server_Account $a
     * @param type $new - new domain name
     */
    public function changeAccountDomain(Server_Account $a, $new)
    {
        {
            throw new Server_Exception('Server Manager Vesta CP Error: Not Supported');
        }
    }
    /**
     * Change account password on server
     * @param Server_Account $a
     * @param type $new - new password
     */
    public function changeAccountPassword(Server_Account $a, $new)
    {


        // Server credentials
        $vst_username = $this->_config['username'];
        $vst_password = $this->_config['password'];
        $vst_command = 'v-change-user-password';
        $vst_returncode = 'yes';
// Prepare POST query
        $postvars = array(

            'returncode' => $vst_returncode,
            'cmd' => $vst_command,
            'arg1' => $a->getUsername(),
            'arg2' => $new

        );
// Make request and change password
        $result = $this->_makeRequest($postvars);
        if($result != '0'){
            throw new Server_Exception('Server Manager Vesta CP Error: Change Password Account Error '.$result);
        }

        return true;
    }
    /**
     * Change account IP on server
     * @param Server_Account $a
     * @param type $new - account IP
     */
    public function changeAccountIp(Server_Account $a, $new)
    {

        {
            throw new Server_Exception('Server Manager Vesta CP Error: Not Supported');
        }
    }
}

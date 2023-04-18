<?php
// Centos Web Panel Server Manager For BoxBilling http://www.boxbilling.com/
// This source file is subject to the license that is bundled
// with this package in the file LICENSE.txt
// Created by Grant Bamford https://www.speeddemon.co.za/
// Version 1.0 (8/8/2020)

class Server_Manager_Cwp extends Server_Manager
{
    public function init()
    {
        if (!extension_loaded('curl')) {
            throw new Server_Exception('cURL extension is not enabled');
        }

        $this->_config['version'] = '1.0';
    }

    public static function getForm()
    {
        return array(
            'label'     =>  'Centos Web Panel',
        );
    }

    public function getLoginUrl()
    {
        if ($this->_config['secure']) {
            return 'http://'.$this->_config['host'] . ':2083';
        } else {
            return 'https://'.$this->_config['host'] . ':2083';
        }
    }

    public function getResellerLoginUrl()
    {
        return $this->getLoginUrl();
    }

    private function _makeRequest($data, $endpoint)
    {
        $this->getLog()->debug(sprintf('Control Web Panel endpoint "%s" called with params: "%s" ', $endpoint, print_r($data,1)));
        $host = 'http';
        if ($this->_config['secure']) {
            $host .= 's';
        }
        $port = '2304';
        if ($this->_config['port']){
            $port = $this->_config['port'];
        }
        $host .= '://' . $this->_config['host'] . ':' . $port . '/v1/' . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = curl_exec($ch);

        if (curl_errno ($ch)) {
            throw new Server_Exception('Error connecting to server : ' . curl_errno ($ch) . ' - ' . curl_error ($ch));
        }
        curl_close($ch);
        if (preg_match("/OK/i", $response) || ($endpoint == 'packages' && preg_match("/Name already exists/i", $response))) {
            $result = true;

        } else {
            $json = json_decode($response);
            if ($json === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Server_Exception('The server sent back an invalid response');
            }else{
                $message = $json->msj;
                throw new Server_Exception($message);
            }
        }
        return $result;
    }
    private function _getFromAPI($data, $endpoint)
    {
        $this->getLog()->debug(sprintf('Control Web Panel endpoint "%s" called with params: "%s" ', $endpoint, print_r($data,1)));
        $host = 'http';
        if ($this->_config['secure']) {
            $host .= 's';
        }
        $port = '2304';
        if ($this->_config['port']){
            $port = $this->_config['port'];
        }
        $host .= '://' . $this->_config['host'] . ':' . $port . '/v1/' . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $response = curl_exec($ch);

        $arrayReturn=json_decode($response);
        if($arrayReturn->status=='Error'){
            return array('status'=>'Error','msj'=>$arrayReturn->msj." ".$arrayReturn->result);
        }else{
            if(isset($arrayReturn->msj) && $arrayReturn->msj!=''){
                return $arrayReturn->msj;
            }elseif(isset($arrayReturn->result) && $arrayReturn->result!=''){
                return $arrayReturn->result;
            }else{
                return $arrayReturn->status;
            }
        }
    }
    public function testConnection()
    {
        $this->getLog()->info('Testing Connection');
        $data = array('key' => $this->_config['accesshash'], 'action' => 'list');
        $endpoint = 'account';
        $result = $this->_makeRequest($data, $endpoint);
        if (isset($result['return']) && $result['return'] == true) {
            return true;
        }
        return false;
    }

    public function synchronizeAccount(Server_Account $a)
    {
        $this->getLog()->info('Synchronizing account with server '.$a->getUsername());
        return $a;
    }

    public function createAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Creating reseller hosting account');
        } else {
            $this->getLog()->info('Creating shared hosting account');
            $p = $a->getPackage();
            $c = $a->getClient();
            $package_data = $p->getPackagesAllValues();
            $package_cwp = array_merge([
                'key' => $this->_config['accesshash'],
                'action' => 'add',
                'package_name' =>	$package_data['name'],//Package Name
                'disk_quota' =>	$package_data['quota'],//MB disk space
                'bandwidth' =>	$package_data['bandwidth'],//Bandwidth
                'ftp_accounts' =>	$package_data['maxftp'],//Number of FTP accounts
                'email_accounts' =>	$package_data['maxpop'],//Number of e-mail accounts
                'databases' =>	$package_data['maxsql'],//Number of Data Base Mysql/MariaDB
                'sub_domains' =>	$package_data['maxsubdomains'],//Number of Max Sub Domains
                'parked_domains' =>	$package_data['maxparkeddomains'],//Number of Max Parked Domains
                'addons_domains' =>	$package_data['maxdomains'],//Number of Max Addons Domains
                //'hourly_emails' =>	$package_data[''],//Number of Max hourly emails
            ], $p->getPackagesAllCustomValues());
            $endpoint = 'packages';
            $result = $this->_makeRequest($package_cwp, $endpoint);

            $data = array(
                'key' => $this->_config['accesshash'],
                'action' => 'add',
                'domain' => $a->getDomain(),
                'user' => $a->getUsername(),
                'pass' => $a->getPassword(),
                'email' => $c->getEmail(),
                'package' => $package_data['name'],
                'inode' => $p->getCustomValue('inode'),
                'limit_nproc' => $p->getCustomValue('limit_nproc'),
                'limit_nofile' => $p->getCustomValue('limit_nofile'),
                'server_ips' => $this->_config['ip']
            );
            $endpoint = 'account';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function suspendAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Suspending reseller hosting account');
        } else {
            $this->getLog()->info('Suspending shared hosting account');
            $data = array('key' => $this->_config['accesshash'], 'action' => 'susp', 'user' => $a->getUsername());
            $endpoint = 'account';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function unsuspendAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Unsuspending reseller hosting account');
        } else {
            $this->getLog()->info('Unsuspending shared hosting account');
            $data = array('key' => $this->_config['accesshash'], 'action' => 'unsp', 'user' => $a->getUsername());
            $endpoint = 'account';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function cancelAccount(Server_Account $a)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Cancelling reseller hosting account');
        } else {
            $this->getLog()->info('Cancelling shared hosting account');
            $c = $a->getClient();
            $data = array('key' => $this->_config['accesshash'], 'action' => 'del', 'user' => $a->getUsername(), 'email' => $c->getEmail());
            $endpoint = 'account';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function changeAccountPackage(Server_Account $a, Server_Package $p)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Updating reseller hosting account');
        } else {
            $this->getLog()->info('Updating shared hosting account');
            $c = $a->getClient();
            $p = $a->getPackage();
            $data = array(
                'key' => $this->_config['accesshash'],
                'action' => 'upd',
                'domain' => $a->getDomain(),
                'user' => $a->getUsername(),
                'pass' => $a->getPassword(),
                'email' => $c->getEmail(),
                'package' => $p->getCustomValue('packageid'),
                'inode' => $p->getCustomValue('inode'),
                'limit_nproc' => $p->getCustomValue('limit_nproc'),
                'limit_nofile' => $p->getCustomValue('limit_nofile'),
                'server_ips' => $this->_config['ip']
            );
            $endpoint = 'account';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function changeAccountUsername(Server_Account $a, $new)
    {
        throw new Server_Exception('Server manager does not support username changes');
    }

    public function changeAccountDomain(Server_Account $a, $new)
    {
        throw new Server_Exception('Server manager does not support domain changes');
    }

    public function changeAccountPassword(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account password');
        } else {
            $this->getLog()->info('Changing shared hosting account password');
            $data = array('key' => $this->_config['accesshash'], 'action'=>'udp' , 'user' => $a->getUsername(), 'pass' => $new);
            $endpoint = 'changepass';
            $result = $this->_makeRequest($data, $endpoint);
            if (isset($result['return']) && $result['return'] == true) {
                return true;
            }
            return false;
        }
    }

    public function changeAccountIp(Server_Account $a, $new)
    {
        throw new Server_Exception('Server manager does not support ip changes');
    }

    public function create_email(Server_Account $a,$new){
        $data = array('key' => $this->_config['accesshash'],
            'action'=>'add' ,
            'user' => $a->getUsername(),
            'email'=>$new['email'],
            'domain'=>$new['domain'],
            'pass'=>$new['password'],
            'quotamail'=>'200',
            'debug'=>0
            );
        $endpoint = 'email';

        return $this->_getFromAPI($data, $endpoint);
    }
    public function getQuota(Server_Account $a){
        $result=array();
        $arrayModulos=array(array("module"=>"email_accounts","icon"=>"far fa-envelope","name"=>"Mail Box","url"=>""),
                array("module"=>"forwarders_email","icon"=>"far fa-envelope-open","name"=>"Mail Forwarders","url"=>""),
                array("module"=>"mail_autoreply","icon"=>"fa fa-reply-all","name"=>"AutoResponder","url"=>""),
                array("module"=>"backups","icon"=>"fa fa-cube","name"=>"Backups","url"=>""),
                array("module"=>"domains","icon"=>"fa fa-globe","name"=>"Domains","url"=>""),
                array("module"=>"subdomains","icon"=>"fa fa-plus-circle","name"=>"Sub Domains","url"=>""),
                array("module"=>"crontab","icon"=>"fa fa-cubes","name"=>"Cron Jobs","url"=>""),
                array("module"=>"mysql_manager","icon"=>"fa fa-database","name"=>"Mysql Manager","url"=>"")
                );
            //$this->getLog()->info('Changing shared hosting account password');
            $data = array('key' => $this->_config['accesshash'], 'action'=>'list' , 'user' => $a->getUsername());
        $endpoint = 'accountdetail';
        $result['stat']= $this->_getFromAPI($data, $endpoint);

            foreach($arrayModulos as $index => $modulo){
                $data = array('key' => $this->_config['accesshash'], 'action'=>'list' , 'user' => $a->getUsername(),'module'=>$modulo['module']);
                $endpoint = 'user_session';
                $urlMenu = $this->_getFromAPI($data, $endpoint);

                $arrayModulos[$index]['url']=$urlMenu->details[0]->url;
            }
            $result['shortcut']=$arrayModulos;

            return $result;

    }
    public function accountDetails(){
        $data = array(
            'key' => $this->_config['accesshash'],
            'action' => 'list',
        );
        $endpoint = 'account';
        $result = $this->_getFromAPI($data, $endpoint);
        if (isset($result['status']) && $result['status'] == 'OK') {
            return $result['msj'];
        }else{
            return $result;
        }
    }
}

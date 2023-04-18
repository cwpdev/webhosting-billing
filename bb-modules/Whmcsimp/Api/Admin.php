<?php
/**
 * BoxBilling
 *
 * @copyright BoxBilling, Inc (https://www.boxbilling.org)
 * @license   Apache-2.0
 *
 * Copyright BoxBilling, Inc
 * This source file is subject to the Apache-2.0 License that is bundled
 * with this source code in the file LICENSE
 */

/**
 * whmcsimp module Admin API
 * 
 * API can be access only by admins
 */

namespace Box\Mod\Whmcsimp\Api;


class Admin extends \Api_Abstract
{protected $di = null;

    /**
     * @param Box_Di|null $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return Box_Di|null
     */
    public function getDi()
    {
        return $this->di;
    }
    /**
     * Return list of whmcsimp objects
     * 
     * @return string[]
     */
    public function get_something($data)
    {
        if(isset($data['acc'])){
            $apiWhmcs=$this->dataApiWhmcs();
            if($apiWhmcs){
                if($data['acc']=='customers-check'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetClients',
                        'limitnum'=>1000,
                        'responsetype' => 'json',
                    );;
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    return ($curl->result == 'success')?$curl->totalresults:json_encode($curl);
                }
                if($data['acc']=='products-check'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetProducts',
                        'limitnum'=>1000,
                        'responsetype' => 'json',
                    );
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    return ($curl->result == 'success')?$curl->totalresults:$curl;
                }
                if($data['acc']=='allOrden'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetOrders',
                        'limitnum'=>1000,
                        'responsetype' => 'json',
                    );
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    return ($curl->result == 'success')?$curl->totalresults:$curl;
                }
                if($data['acc']=='allinvoices'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetInvoices',
                        'limitnum'=>1000,
                        'responsetype' => 'json',
                    );
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    return ($curl->result == 'success')?$curl->totalresults:$curl;
                }
                if($data['acc']=='alltickets'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetTickets',
                        'limitnum'=>100000,
                        'responsetype' => 'json',
                    );
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    $fileCustomWhmcs = shell_exec("curl -k '{$apiWhmcs->url}custom_api.php'");
                    if(trim($fileCustomWhmcs)=='File not found.'){$file="<span style=\"color:#FA0118\"\">Not found {$apiWhmcs->url}custom_api.php</span> <a href='https://google.com' target='_blank'>See details</a>";}else{$file="";}
                    $return = ($curl->result == 'success')?['totalresults'=>$curl->totalresults,'file'=>$file]:$curl->message;
                    return $return;
                }
                if($data['acc']=='customer_products'){
                    $postfields = array(
                        'identifier' => $apiWhmcs->identifier,
                        'secret' => $apiWhmcs->secret,
                        'action' => 'GetClientsProducts',
                        'limitnum'=>100000,
                        'responsetype' => 'json',
                    );
                    $curl = $this->get_curl($apiWhmcs,$postfields);
                    return ($curl->result == 'success')?$curl->totalresults:$curl->message;
                }
                if($data['acc']=='save_data'){
                    $required = array(
                        'url'    => 'enter the url',
                        'identifier'      => 'Missing the identifier',
                        'secret'      => 'You must indicate a secret code',
                    );
                    $this->di['validator']->checkRequiredParamsForArray($required, $data);

                    // Retrieve associated server
                    $whmcsdata  = $this->di['db']->findOne('whmcsimport','id=:id',array(':id'=>1));

                    $whmcsdata->url     		= $data['url'];
                    $whmcsdata->identifier    	= $data['identifier'];
                    $whmcsdata->secret    		= $data['secret'];
                    $whmcsdata->created_at    	= date('Y-m-d H:i:s');
                    $whmcsdata->updated_at    	= date('Y-m-d H:i:s');
                    $this->di['db']->store($whmcsdata);

                    $this->di['logger']->info('Update  %s', $whmcsdata->id);
                    return 1;
                }
                if($data['acc']=='process'){
                    $apiWhmcs=$this->dataApiWhmcs();
                    $tzData = explode(":",$data['data']);
                    $rep =(object)[];
                    if(count($tzData)>0){
                        if(in_array('customers-check',$tzData)){
                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetClients',
                                'limitnum'=>10000,
                                'responsetype' => 'json',
                            );
                            $curl = $this->get_curl($apiWhmcs,$postfields);
                            $clientes = $curl->clients->client;
                            $service = $this->getService();
                            $duplClients=[];
                            $addClients =0;
                            foreach($clientes as $dataClient){
                                if($service->emailAreadyRegistered($dataClient->email)) {
                                    $duplClients[$dataClient->email];
                                }else{
                                    $details = $this->detailClient($apiWhmcs,$dataClient->email);
                                    $pass = $this->GetClientPassword($apiWhmcs,$dataClient->email);
                                    if(empty($pass)){
                                        sleep(2);
                                        $pass = $this->GetClientPassword($apiWhmcs,$dataClient->email);
                                    }
                                    if(strtolower($details->status)=='inactive'){$status ='suspended';}else
                                    if(strtolower($details->status)=='Closed'){$status ='closed';}else{$status ='active';}
                                    $data =[
                                        'id'=>$details->client_id,
                                        'email'=>$details->email,
                                        'password'=>'123qaz',
                                        'auth_type'=>'',
                                        'first_name'=>$details->firstname,
                                        'phone_cc'=>$details->phonecc,
                                        'aid'=>'',
                                        'last_name'=>$details->lastname,
                                        'group_id'=>1,
                                        'status'=>$status,
                                        'gender'=>'',
                                        'birthday'=>'1990-01-01',
                                        'phone'=>$details->telephoneNumber,
                                        'company'=>$details->companyname,
                                        'company_vat'=>'',
                                        'company_number'=>'',
                                        'type'=>'',
                                        'address_1'=>substr($details->address1,0,100),
                                        'address_2'=>substr($details->address2,0,100),
                                        'city'=>substr($details->city,0,100),
                                        'state'=>substr($details->state,0,100),
                                        'postcode'=>$details->postcode,
                                        'country'=>substr($details->country,0,100),
                                        'document_type'=>'',
                                        'document_nr'=>'',
                                        'notes'=>$details->notes,
                                        'lang'=>substr($details->language,0,10),
                                        'currency'=>substr($details->currency_code,0,10),
                                        'custom_1'=>'',
                                        'custom_2'=>'',
                                        'custom_3'=>'',
                                        'custom_4'=>'',
                                        'custom_5'=>'',
                                        'custom_6'=>'',
                                        'custom_7'=>'',
                                        'custom_8'=>'',
                                        'custom_9'=>'',
                                        'custom_10'=>'',
                                        'pass'=>$pass,
                                    ];
                                    $service->createClient($data);
                                    //$service->passNewUser($details->email,$pass);

                                    $addClients++;
                                }
                            }
                            $rep->clients->migrate=$addClients;
                            $rep->clients->duplicate=$duplClients;
                        }
                        if(in_array('products-check',$tzData)){
                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetProducts',
                                'responsetype' => 'json',
                            );
                            $curl = $this->get_curl($apiWhmcs,$postfields);
                            $products = $curl->products->product;
                            $service = $this->getService();
                            $this->importServers($service,$apiWhmcs);
                            foreach($products as $dataP){
                                if(isset($dataP->type) && !empty($dataP->type)){
                                    $resp .= $service->createProductoImport($dataP);
                                }
                            }
                            // FALTA AGREGAR PRECIOS
                            // FALTA MIGRAR LOS CAMPOS PERSONALIZADOS
                            return $resp;
                        }
                        if(in_array('customer_products',$tzData)){
                            $service = $this->getService();
                            $clients= $service->getAllClients();
                            foreach($clients as $client){
                                $email = $client['email'];
                                $idClient = $client['id'];
                                $postfields = array(
                                    'identifier' => $apiWhmcs->identifier,
                                    'secret' => $apiWhmcs->secret,
                                    'action' => 'GetClients',
                                    'limitnum'=>100000,
                                    'search'=>$email,
                                    'responsetype' => 'json',
                                );
                                $curl = $this->get_curl($apiWhmcs,$postfields);
                                $id_clieteWhmcs =$curl->clients->client[0]->id;
                                $GetClientsProducts = array(
                                    'identifier' => $apiWhmcs->identifier,
                                    'secret' => $apiWhmcs->secret,
                                    'action' => 'GetClientsProducts',
                                    'responsetype' => 'json',
                                    'clientid'=>$id_clieteWhmcs,
                                );
                                $curl = $this->get_curl($apiWhmcs,$GetClientsProducts);
                                $products = $curl->products->product;

                                $curl = $this->get_curl($apiWhmcs,$GetClientsProducts);
                                $products = $curl->products->product;
                                //GetTLDPricing

                                $GetClientsPTls = array(
                                    'identifier' => $apiWhmcs->identifier,
                                    'secret' => $apiWhmcs->secret,
                                    'action' => 'GetTLDPricing',
                                    'responsetype' => 'json',
                                    'clientid'=>$id_clieteWhmcs,
                                );
                                $tlscurl = $this->get_curl($apiWhmcs,$GetClientsPTls);
                                $tls = $tlscurl->pricing;

                                $exts =[];
                                foreach($tls as $key => $value){
                                    $exts[]=$key;
                                }
                                $service->InsertProductClientWhmcs($products,$id_clieteWhmcs,$idClient,$exts);
                            }
                            return true;
                        }
                        if(in_array('alltickets',$tzData)){
                            $service = $this->getService();
                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetSupportDepartments',
                                'limitnum'=>10000,
                                'responsetype' => 'json',
                            );
                            $GetSupportDepartments = $this->get_curl($apiWhmcs,$postfields);

                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetAdminUsers',
                                'limitnum'=>10000,
                                'responsetype' => 'json',
                            );
                            $GetAdminUsers = $this->get_curl($apiWhmcs,$postfields);

                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetTickets',
                                'responsetype' => 'json',
                                'limitnum'=>100000,
                            );
                            $GetTickets = $this->get_curl($apiWhmcs,$postfields);

                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'type' => 'support',
                            );
                            $custom_fields = $this->get_curl($apiWhmcs,$postfields,'custom_api.php');

                            $dataTicket ='';
                            $dataTicket->supportDepartments = $GetSupportDepartments;
                            $dataTicket->adminUsers = $GetAdminUsers;
                            $dataTicket->tickets = $GetTickets;
                            $dataTicket->custom_fields = $custom_fields;
                            $support = $service->migrationSupport($dataTicket,$apiWhmcs);
                            return $support;
                        }
                        if(in_array('allOrden',$tzData)){
                            $service = $this->getService();
                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetClients',
                                'limitnum'=>10000,
                                'responsetype' => 'json',
                            );
                            $GetClients = $this->get_curl($apiWhmcs,$postfields);
                            $contOrden =0;
                            foreach($GetClients->clients->client as $client){
                                $postfieldsCliente = array(
                                    'identifier' => $apiWhmcs->identifier,
                                    'secret' => $apiWhmcs->secret,
                                    'action' => 'GetOrders',
                                    'userid' =>$client->id,
                                    'limitnum'=>10000,
                                    'responsetype' => 'json',
                                );
                                $GetOrdens = $this->get_curl($apiWhmcs,$postfieldsCliente);
                                $support = $service->migrationOrdens($client->email,$GetOrdens);
                                $contOrden++;
                            }
                            return $contOrden;
                        }
                        if(in_array('allinvoices',$tzData)){
                            $service = $this->getService();
                            $postfields = array(
                                'identifier' => $apiWhmcs->identifier,
                                'secret' => $apiWhmcs->secret,
                                'action' => 'GetClients',
                                'limitnum'=>10000,
                                'responsetype' => 'json',
                            );
                            $GetClients = $this->get_curl($apiWhmcs,$postfields);
                            $contInvoices =0;
                            foreach($GetClients->clients->client as $client){
                                $postfieldsCliente = array(
                                    'identifier' => $apiWhmcs->identifier,
                                    'secret' => $apiWhmcs->secret,
                                    'action' => 'GetInvoices',
                                    'userid' =>$client->id,
                                    'limitnum'=>10000,
                                    'responsetype' => 'json',
                                );
                                $Getinvoices = $this->get_curl($apiWhmcs,$postfieldsCliente);
                                $Invoices = $service->migrationInvoices($client,$Getinvoices,$apiWhmcs);
                                $contInvoices++;
                            }
                            return $contInvoices;

                        }

                    }else{
                        return 'falta data';
                    }
                }
                if($data['acc']=='getConfigData'){
                    $apiWhmcs=$this->dataApiWhmcs();
                    return $apiWhmcs;
                }

            }else{
                return ['result'=>'error','msj'=>'can\'t get connection with whmcs'];
            }
        }
    }
    public function dataApiWhmcs(){
        $server = $this->di['db']->dispense('whmcsimport');
        $return = $this->di['db']->getAll("SELECT * FROM whmcsimport WHERE id = 1");
        return (object)$return[0];
    }
    private function get_curl($apiWhmcs,$postfields,$fileUrl =''){
        // Call the API
        $fileUrl = (trim($fileUrl)=='')?'includes/api.php':$fileUrl;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiWhmcs->url . $fileUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
        $response = curl_exec($ch);
        if (curl_error($ch)) {
            die('Unable to connect: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);
        $jsonData = json_decode($response);
        return $jsonData;
    }
    public function detailClient($apiWhmcs,$email){
        $postfields = array(
            'identifier' => $apiWhmcs->identifier,
            'secret' => $apiWhmcs->secret,
            'action' => 'GetClientsDetails',
            'responsetype' => 'json',
            'email'=>$email,
        );
        $curl = $this->get_curl($apiWhmcs,$postfields);
        return (isset($curl->result) && $curl->result=='success')?$curl:false;
    }
    private function GetClientPassword($apiWhmcs,$email){
        $postfields = array(
            'identifier' => $apiWhmcs->identifier,
            'secret' => $apiWhmcs->secret,
            'action' => 'GetClientPassword',
            'responsetype' => 'json',
            'email'=>trim($email),
        );
        $curl = $this->get_curl($apiWhmcs,$postfields);
        if((isset($curl->result) && $curl->result=='success')){
            //$StPass=$this->DecryptPassword($apiWhmcs,trim($curl->password));
            $StPass=trim($curl->password);
            return $StPass;
        }else{
            return false;
        }
    }
    private function  DecryptPassword($apiWhmcs,$pass){
        $postfields = array(
            'identifier' => $apiWhmcs->identifier,
            'secret' => $apiWhmcs->secret,
            'action' => 'DecryptPassword',
            'password2'=>$pass,
        );
        return $pass;
        /*$curl = $this->get_curl($apiWhmcs,$postfields);
        return (isset($curl->result) && $curl->result=='success')?$pass:false;*/
    }
    private function importServers($service,$apiWhmcs){
        $postfields = array(
            'identifier' => $apiWhmcs->identifier,
            'secret' => $apiWhmcs->secret,
            'action' => 'GetServers',
            'responsetype' => 'json',
        );
        $curl = $this->get_curl($apiWhmcs,$postfields);
        $servers = $curl->servers;
        $service->impServers($servers);
    }
}
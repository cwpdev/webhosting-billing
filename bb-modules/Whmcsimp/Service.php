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
 * This file is a delegate for module. Class does not extend any other class
 *
 * All methods provided in this example are optional, but function names are
 * still reserved.
 *
 */

namespace Box\Mod\Whmcsimp;

class Service
{
    const CUSTOM = 'custom';
    const LICENSE = 'license';
    const ADDON = 'addon';
    const DOMAIN = 'domain';
    const DOWNLOADABLE = 'downloadable';
    const HOSTING = 'hosting';
    const MEMBERSHIP = 'membership';
    const VPS = 'vps';
    //const SERVER = 'server';

    const SETUP_AFTER_ORDER = 'after_order';
    const SETUP_AFTER_PAYMENT = 'after_payment';
    const SETUP_MANUAL = 'manual';

    protected $di;
    private $apiWhmcs;

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }
    public function install()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `whmcsimport` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `url` varchar(255) DEFAULT NULL,
            `identifier` varchar(255) DEFAULT NULL,
            `secret` varchar(255) DEFAULT NULL,
            `created_at` varchar(35) DEFAULT NULL,
            `updated_at` varchar(35) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->di['db']->exec($sql);

        $model                	= $this->di['db']->dispense('whmcsimport');
        $model->id     	        = '';
        $model->url     	    = 'https://';
        $model->identifier     	= '';
        $model->secret     	    = '';
        $model->created_at    	= date('Y-m-d H:i:s');
        $model->updated_at    	= date('Y-m-d H:i:s');
        $this->di['db']->store($model);
        return $model;
    }
    public function uninstall()
    {
        $this->di['db']->exec("DROP TABLE IF EXISTS `whmcsimport`");
        return true;
    }
    public function update($manifest)
    {
        //throw new \Box_Exception("Throw exception to terminate module update process with a message", array(), 125);
        return true;
    }
    public function getConfig()
    {
        $sql = 'SELECT * FROM whmcsimport WHERE id = 1';
        $rows   = $this->di['db']->getAll($sql);
        $result['url']  = $rows['url'];
        $result['identifier']  = $rows['identifier'];
        $result['secret']  = $rows['secret'];

        return $result;
    }
    public function getAllClients(){
        $sql = 'SELECT * FROM client ORDER BY `email`';
        $rows   = $this->di['db']->getAll($sql);
        return $rows;
    }
    public function createClient(array $data)
    {
        $password = $this->di['array_get']($data, 'password', uniqid());

        $client = $this->di['db']->dispense('Client');

        $client->auth_type  = $this->di['array_get']($data, 'auth_type');
        $client->email      = strtolower(trim($this->di['array_get']($data, 'email')));
        $client->first_name = ucwords($this->di['array_get']($data, 'first_name'));
        //$client->pass       = $this->di['password']->hashIt($password);
        $client->pass       = $this->di['array_get']($data, 'pass');

        $phoneCC = $this->di['array_get']($data, 'phone_cc', $client->phone_cc);
        if(!empty($phoneCC)){
            $client->phone_cc = intval($phoneCC);
        }
        //$client->id             = $this->di['array_get']($data, 'id');
        $client->aid             = $this->di['array_get']($data, 'id');
        $client->last_name       = $this->di['array_get']($data, 'last_name');
        $client->client_group_id = $this->di['array_get']($data, 'group_id');
        $client->status          = $this->di['array_get']($data, 'status');
        $client->gender          = $this->di['array_get']($data, 'gender');
        $client->birthday        = $this->di['array_get']($data, 'birthday');
        $client->phone           = $this->di['array_get']($data, 'phone');
        $client->company         = $this->di['array_get']($data, 'company');
        $client->company_vat     = $this->di['array_get']($data, 'company_vat');
        $client->company_number  = $this->di['array_get']($data, 'company_number');
        $client->type            = $this->di['array_get']($data, 'type');
        $client->address_1       = $this->di['array_get']($data, 'address_1');
        $client->address_2       = $this->di['array_get']($data, 'address_2');
        $client->city            = $this->di['array_get']($data, 'city');
        $client->state           = $this->di['array_get']($data, 'state');
        $client->postcode        = $this->di['array_get']($data, 'postcode');
        $client->country         = $this->di['array_get']($data, 'country');
        $client->document_type   = $this->di['array_get']($data, 'document_type');
        $client->document_nr     = $this->di['array_get']($data, 'document_nr');
        $client->notes           = $this->di['array_get']($data, 'notes');
        $client->lang            = $this->di['array_get']($data, 'lang');
        $client->currency        = $this->di['array_get']($data, 'currency');


        $client->custom_1  = $this->di['array_get']($data, 'custom_1');
        $client->custom_2  = $this->di['array_get']($data, 'custom_2');
        $client->custom_3  = $this->di['array_get']($data, 'custom_3');
        $client->custom_4  = $this->di['array_get']($data, 'custom_4');
        $client->custom_5  = $this->di['array_get']($data, 'custom_5');
        $client->custom_6  = $this->di['array_get']($data, 'custom_6');
        $client->custom_7  = $this->di['array_get']($data, 'custom_7');
        $client->custom_8  = $this->di['array_get']($data, 'custom_8');
        $client->custom_9  = $this->di['array_get']($data, 'custom_9');
        $client->custom_10 = $this->di['array_get']($data, 'custom_10');

        $client->ip = $this->di['array_get']($data, 'ip');

        $created_at = $this->di['array_get']($data, 'created_at');
        $client->created_at = !empty($created_at) ? date('Y-m-d H:i:s', strtotime($created_at)) : date('Y-m-d H:i:s');
        $client->updated_at = date('Y-m-d H:i:s');
        $this->di['db']->store($client);
        return $client;
    }
    public function emailAreadyRegistered($new_email)
    {
        $result = $this->di['db']->findOne('Client', 'email = ?', array($new_email));
        return ($result) ? true : false;
    }
    public function getTypes()
    {
        $data = array(
            self::CUSTOM       => 'Custom',
            self::LICENSE      => 'License',
            self::DOWNLOADABLE => 'Downloadable',
            self::HOSTING      => 'Hosting',
           // self::SERVER      => 'Server',
            self::DOMAIN       => 'Domain',
        );

        // attach service modules
        $extensionService = $this->di['mod_service']('extension');
        $list             = $extensionService->getInstalledMods();
        foreach ($list as $mod) {
            if (substr($mod, 0, strlen('service')) == 'service') {
                $n        = substr($mod, strlen('service'));
                $data[$n] = ucfirst($n);
            }
        }

        return $data;
    }
    public function createProduct($title, $type, $categoryId = null)
    {
        $systemService = $this->di['mod_service']('system');
        $systemService->checkLimits('Model_Product', 5);
        $sql      = "SELECT MAX(priority) FROM product LIMIT 1";
        $priority = $this->di['db']->getCell($sql);

        $modelPayment       = $this->di['db']->dispense('ProductPayment');
        $modelPayment->type = \Model_ProductPayment::FREE;
        $paymentId          = $this->di['db']->store($modelPayment);

        $model                      = $this->di['db']->dispense('Product');
        $model->product_payment_id  = $paymentId;
        $model->product_category_id = $categoryId;
        $model->status              = \Model_Product::STATUS_DISABLED;
        $model->title               = $title;
        $model->slug                = $this->di['tools']->slug($title);
        $model->type                = $type;
        $model->setup               = self::SETUP_AFTER_PAYMENT;
        $model->priority            = $priority + 10;
        $model->priority_support    = 25;

        $model->updated_at = date('Y-m-d H:i:s');
        $model->created_at = date('Y-m-d H:i:s');

        // try save with slug
        try {
            $productId = $this->di['db']->store($model);
        } catch (\Exception $e) {
            $model->slug = $this->di['tools']->slug($title) . '-' . rand(1, 9999);
        }
        $productId = $this->di['db']->store($model);
        $modelPayment->id =$productId;
        $this->di['db']->store($modelPayment);
        $this->di['logger']->info('Created new product #%s', $model->id);

        return (int)$productId;
    }
    public function createCategory($title, $description = null, $icon_url = null)
    {
        $systemService = $this->di['mod_service']('system');
        $systemService->checkLimits('Model_ProductCategory', 2);

        $model              = $this->di['db']->dispense('ProductCategory');
        $model->title       = $title;
        $model->description = $description;
        $model->icon_url    = $icon_url;
        $model->updated_at  = date('Y-m-d H:i:s');
        $model->created_at  = date('Y-m-d H:i:s');
        $id                 = $this->di['db']->store($model);

        $this->di['logger']->info('Created new product category #%s', $id);

        return $id;
    }
    public function createProductoImport($produto){
        $types = $this->getTypes();
        if(trim($produto->type) =='hostingaccount' || trim($produto->type) =='server'){$type=strtolower('hosting');}elseif(trim($produto->type) =='other'){$type=strtolower('Custom');}else{$type=strtolower(trim($produto->type));}
        if(!array_key_exists($type, $types)) {
            $this->di['logger']->info('product type does not exist  > '.ucfirst($type).' '.json_encode($types), '');
            return 'product type does not exist :'.$type.' ('.$produto->type.') >>'.json_encode($types);
        }else{
            $categoryId = 1;
            $title = trim($produto->name);
            $status = 'enabled';
            $priority = $this->di['db']->getCell("SELECT MAX(priority) FROM product LIMIT 1");
            $modelPayment=[];
            $modelPayment->type = 1;
            $model                      = $this->di['db']->dispense('Product');
            $model->product_payment_id  = $categoryId;
            $model->product_category_id = $categoryId;
            //$model->status              = \Model_Product::STATUS_DISABLED;
            $model->status              = $status;
            $model->title               = $title;
            $model->description         = $produto->description;
            $model->slug                = $this->di['tools']->slug($title);
            $model->type                = $type;
            $model->setup               = self::SETUP_AFTER_PAYMENT;
            $model->priority            = $priority + 10;
            $model->priority_support    = '25';

            $model->updated_at = date('Y-m-d H:i:s');
            $model->created_at = date('Y-m-d H:i:s');
            try {
                $productId = $this->di['db']->store($model);
                $this->di['logger']->info('Created new product #%s', "{$produto->name} >({$model->id})");

                $idprice = $this->prices($model->id,$produto);
                $model->id = $productId;
                $model->product_payment_id = $idprice;

                $this->di['db']->store($model);
                $this->package($model->id,$produto);
            } catch (\Exception $e) {
                $this->di['logger']->info('Error create Product #%s', $produto->name);
            }
            return (int)$productId;
        }
    }
    public function prices($idProd,$produt){
        $data       = $this->di['db']->dispense('ProductPayment');
        if($produt->paytype=='recurring'){
            $paytype = 'recurrent';
            $once_price = 0;
            $once_setup_price = 0;
            $w_price = 0;
            $m_price=$produt->pricing->USD->monthly;
            $q_price=$produt->pricing->USD->quarterly;
            $b_price = $produt->pricing->USD->semiannually;
            $a_price =  $produt->pricing->USD->annually;
            $bia_price = $produt->pricing->USD->biennially;
            $tria_price = $produt->pricing->USD->triennially;

            $m_setup_price =$produt->pricing->USD->msetupfee;
            $q_setup_price = $produt->pricing->USD->qsetupfee;
            $b_setup_price =$produt->pricing->USD->ssetupfee;
            $a_setup_price =$produt->pricing->USD->asetupfee;
            $bia_setup_price  =$produt->pricing->USD->bsetupfee;
            $tria_setup_price =$produt->pricing->USD->tsetupfee;
            $w_enabled =0;
            $m_enabled =(($produt->pricing->USD->monthly > 0) || ($produt->pricing->USD->msetupfee > 0))?1:0;
            $q_enabled =(($produt->pricing->USD->quarterly > 0) || ($produt->pricing->USD->qsetupfee > 0))?1:0;
            $b_enabled =(($produt->pricing->USD->ssetupfee > 0) || ($produt->pricing->USD->semiannually > 0))?1:0;
            $a_enabled =(($produt->pricing->USD->asetupfee > 0) || ($produt->pricing->USD->annually > 0))?1:0;
            $bia_enabled =(($produt->pricing->USD->biennially > 0) || ($produt->pricing->USD->bsetupfee > 0))?1:0;
            $tria_enabled  =(($produt->pricing->USD->triennially > 0) || ($produt->pricing->USD->tsetupfee > 0))?1:0;
        }else
        if($produt->paytype=='onetime'){
            $paytype = 'once';
            $once_price = $produt->pricing->USD->monthly;
            $once_setup_price = $produt->pricing->USD->msetupfee;
            $w_price = 0;
            $m_price=0;
            $b_price =0;
            $a_price = 0;
            $bia_price =0;
            $tria_price = 0;
            $m_setup_price =0;
            $q_setup_price = 0;
            $b_setup_price =0;
            $a_setup_price =0;
            $bia_setup_price  =0;
            $tria_setup_price =0;
            $w_enabled =0;
            $m_enabled =0;
            $q_enabled =0;
            $b_enabled =0;
            $a_enabled =0;
            $bia_enabled =0;
            $tria_enabled  =0;
        }else
        if($produt->paytype=='free'){
            $paytype = 'free';
            $once_price = '0';
            $once_setup_price = '0';
            $w_price = '0';
            $m_price='0';
            $b_price ='0';
            $a_price = '0';
            $bia_price ='0';
            $tria_price = '0';
            $m_setup_price ='0';
            $q_setup_price = '0';
            $b_setup_price ='0';
            $a_setup_price ='0';
            $bia_setup_price  ='0';
            $tria_setup_price ='0';
            $w_enabled ='0';
            $m_enabled ='0';
            $q_enabled ='0';
            $b_enabled ='0';
            $a_enabled ='0';
            $bia_enabled ='0';
            $tria_enabled  ='0';
        }

        //$data->id = $idProd;
        $data->type = $paytype;
        $data->once_price = $once_price;
        $data->once_setup_price = $once_setup_price;
        $data->w_price = $w_price;
        $data->m_price = $m_price;
        $data->b_price = $b_price;
        $data->a_price = $a_price;
        $data->bia_price = $bia_price;
        $data->tria_price = $tria_price;
        $data->w_setup_price = 0;
        $data->m_setup_price = $m_setup_price;
        $data->q_setup_price = $q_setup_price;
        $data->b_setup_price = $b_setup_price;
        $data->a_setup_price = $a_setup_price;
        $data->bia_setup_price = $bia_setup_price;
        $data->tria_setup_price = $tria_setup_price;
        $data->w_enabled = $w_enabled;
        $data->m_enabled = $m_enabled;
        $data->q_enabled = $q_enabled;
        $data->b_enabled = $b_enabled;
        $data->a_enabled = $a_enabled;
        $data->bia_enabled = $bia_enabled;
        $data->tria_enabled = $tria_enabled;


        $prodTax = $this->di['db']->store($data);

        if(is_numeric($prodTax)){
            $this->di['logger']->info('Created new Prices #%s', "{$prodTax}");
        }else{
            $this->di['logger']->info('Error create PricesID Product ', $idProd);
        }
        return $prodTax;
    }
    public function package($idProd,$produt){
        $nameProd = trim($produt->name);
        $sql = "SELECT COUNT(id) as counter FROM service_hosting_hp WHERE name = '{$nameProd}'";
        $data = $this->di['db']->getRow($sql);
        if($data['counter']==0){
            $data=array();
            $data['name']=$produt->name;
            $data['quota']=1024;
            $data['bandwidth']=1024;
            $data['max_ftp']=1;
            $data['max_sql']=1;
            $data['max_pop']=1;
            $data['max_sub']=1;
            $data['max_park']=1;
            $data['max_addon']=1;
            $data['config']='Null';
            $data['created_at']=date('Y-m-d H:i:s');
            $data['updated_at']=date('Y-m-d H:i:s');
            $data['id_product']=$idProd;
            $this->createHp($nameProd,$data,$produt,$idProd);
        }else{
            $this->di['logger']->info('duplicate record #%s', $nameProd);
        }
    }
    public function newSlug($title){
        $reem =[' ','-','/','\\',"'",'"'];
        return  strtolower(str_replace($reem,'_',$title));
    }
    public function InsertProductClientWhmcs($products,$id_Client,$idClient,$tls){
        $data = array();
        foreach($products as $prod){
            if($prod->billingcycle =='Monthly'){$period ='1M'; }else{$period ='1Y'; }
            $data['currency'] ='';
            $data['period']=$period;
            $data['domain']=$prod->domain;
            $data['quantity']=1;
            $data['activate']=$prod->status;
            $data['invoice_option']='no-invoice';
            $data['skip_validation']=false;
            $data['title']="{$prod->name} for {$prod->domain}";
            $data['price']=$prod->recurringamount;
            $data['notes']="{$prod->notes} > Migrate Whmcs: Orden = {$prod->ordernumber}";
            $data['expires_at']=$prod->nextduedate;
            $data['client_id']=$idClient;
            $data['username']=$prod->username;
            $data['password']=$prod->password;
            $data['serverip']=$prod->serverip;


            if($prod->stats == 'Active'){$data['status'] = 'active';}elseif($prod->stats == 'Terminated'){$data['status'] = 'canceled';}elseif($prod->stats == 'Pending'){$data['status'] = 'pending_setup';}
            $idProduct = $this->di['db']->getRow("SELECT id,type FROM product WHERE title = :title",array(':title'=>trim($prod->name)));
            $data['product_id']=$idProduct['id'];
            $data['service_type']=$idProduct['type'];
            $client  = $this->di['db']->getExistingModelById('Client', $data['client_id'], 'Client not found');
            $product = $this->di['db']->getExistingModelById('Product', $data['product_id'], "Product not found > {$data['product_id']}");
            return $this->createOrder($client, $product, $data,$tls);
        }
    }
    public function createOrder(\Model_Client $client, \Model_Product $product, array $data,$tls)
    {
        $titleProd =isset($data['title']) ? $data['title'] : $product->title;
        $daResp = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM client_order WHERE client_id = {$client->id} AND product_id ={$product->id} AND title ='{$titleProd}'");
        if($daResp['counter']==0){
            $currencyService = $this->di['mod_service']('currency');
            if (isset($data['currency']) && !empty($data['currency'])) {
                $currency = $currencyService->getByCode($data['currency']);
            } elseif ($client->currency) {
                $currency = $currencyService->getByCode($client->currency);
            } else {
                $currency = $currencyService->getDefault();
            }
            if (!$currency instanceof \Model_Currency) {
                $currency->code='USD';
            }
            $this->di['events_manager']->fire(array('event' => 'onBeforeAdminOrderCreate', 'params' => $data, 'subject' => $product->type));

            $period         = (isset($data['period']) && !empty($data['period'])) ? $data['period'] : NULL;
            $qty            = $this->di['array_get']($data, 'quantity', 1);
            $config         = (isset($data['config']) && is_array($data['config'])) ? $data['config'] : array();
            $group_id       = $this->di['array_get']($data, 'group_id');
            $invoiceOption  = $this->di['array_get']($data, 'invoice_option', 'no-invoice');
            $cartService = $this->di['mod_service']('cart');
            $skipValidation = (bool) $this->di['array_get']($data, 'skip_validation', false);

            // check stock
            if (!$cartService->isStockAvailable($product, $qty)) {
                throw new \Box_Exception("Product :id is out of stock.", array(':id' => $product->id), 831);
            }


            // Addons must have defined master order
            $parent_order = FALSE;
            if ($product->is_addon && empty($group_id)) {
                throw new \Box_Exception('Group ID parameter is missing for addon product order', null, 832);
            }
            $this->di['logger']->info('Despues del Addons must have defined  #%s', '');
            //validate order config data
            if ($period) {
                $config['period'] = $period;
            }

            $se = $this->di['mod_service']('service' . $product->type);
            $tz=explode('.',$data['domain']);
            $tls = str_replace($tz[0],'',$data['domain']);

            $domainTls = $this->split_domain($data['domain'],$tls);
            $config['domain']=array('action'=> 'owndomain','owndomain_sld'=>$tz[0],'owndomain_tld'=>$tls);

            $order                 = $this->di['db']->dispense('ClientOrder');
            $order->client_id      = $client->id;
            $order->product_id     = $product->id;
            $order->group_id       = ($parent_order) ? $parent_order->group_id : uniqid();
            $order->group_master   = ($parent_order) ? 0 : 1;
            $order->title          = isset($data['title']) ? $data['title'] : $product->title;
            $order->currency       = $currency->code;
            $order->quantity       = $qty;
            $order->service_type   = $product->type;
            $order->unit           = $product->unit;
            $order->status         = $data['activate'];
            $order->expires_at     = $data['expires_at'].' 01:00:00';


            $mainDomain = (!empty($domainTls->subdomain))?"{$domainTls->subdomain}.{$domainTls->domain}":$domainTls->domain;
            $service_hosting_server = $this->InfoHost($data['serverip']);

            if($product->type =='hosting'){
                $datServ = array();
                $datServ['client_id']                   = $client->id;
                $datServ['service_hosting_server_id']   = 1;
                $datServ['service_hosting_hp_id']       = $product->id;
                $datServ['sld']                         = $mainDomain;
                $datServ['tld']                         = $domainTls->tls;
                $datServ['ip']                          = $data['serverip'];
                $datServ['username']                    = $data['username'];
                $datServ['pass']                        = $data['password'];
                $datServ['reseller']                    = 0;

                $order->service_id = $this->InfoServiceHosting($datServ);
            }

            $order->config =json_encode([
                "server_apply"=>"all",
                "hosting_plan_id"=>$product->id,
                "reseller"=>"0",
                "free_domain"=>"0",
                "free_transfer"=>"0",
                "server_picked"=>[],
                "import"=>"0",
                "domain"=>[
                    "owndomain_sld"=>$mainDomain,
                    "owndomain_tld"=>$domainTls->tls,
                    "action"=>"owndomain",
                ],
                "username"=>$data['username'],
                "password"=>$data['password'],
                "password_secured"=>false,
                "period"=>$period,
                "sld"=>$mainDomain,
                "tld"=>$domainTls->tls,
                "server_id"=>$service_hosting_server->id,
            ]);

            if ($period) {
                $bp            = $this->di['period']($data['period']);
                $order->period = $bp->getCode();
            }

            if (isset($data['price'])) {
                $order->price = $data['price'];
            } else {
                $repo = $product->getTable($product->type);
                $rate         = $currencyService->getRateByCode($currency->code);
                $order->price = $repo->getProductPrice($product, $config) * $rate;
            }

            $order->notes = $this->di['array_get']($data, 'notes', $order->notes);
            $order->created_at = (isset($data['created_at'])) ?date('Y-m-d H:i:s', strtotime($data['created_at'])):date('Y-m-d H:i:s');
            $order->updated_at = (isset($data['updated_at']))?date('Y-m-d H:i:s', strtotime($data['updated_at'])):date('Y-m-d H:i:s');
            $id = $this->di['db']->store($order);
            $order->id = $id;
            $this->di['logger']->info('Se creo la orden  #%s', $id);
            if (isset($data['meta']) && is_array($data['meta'])) {
                foreach ($data['meta'] as $k => $v) {
                    $mm = $this->di['db']->findOne('client_order_meta', 'client_order_id = :id AND name = :n', array(':id' => $order->id, ':n' => $k));
                    if (!$mm) {
                        $mm                  = $this->di['db']->dispense('ClientOrderMeta');
                        $mm->client_order_id = $id;
                        $mm->name            = $k;
                        $mm->created_at      = date('Y-m-d H:i:s');
                    }
                    $mm->value      = $v;
                    $mm->updated_at = date('Y-m-d H:i:s');
                    $this->di['db']->store($mm);
                }
            }

            $this->di['events_manager']->fire(array('event' => 'onAfterAdminOrderCreate', 'params' => array('id' => $order->id), 'subject' => $product->type));
            $this->di['logger']->info('Created order #%s', $id);

            $this->activateOrder($order);
            return $id;
        }
    }
    public function getMasterOrderForClient(\Model_Client $client, $group_id)
    {
        $bindings = array(
            ':group_id'  => $group_id,
            ':client_id' => $client->id
        );

        return $this->di['db']->findOne('ClientOrder', 'group_id = :group_id AND group_master = 1 AND client_id = :client_id', $bindings);
    }
    public function activateOrderAddons(\Model_ClientOrder $order)
    {
        /*if (!$order->group_master) {
            return false;
        }

        $list = $this->getOrderAddonsList($order);
        foreach ($list as $addon) {
            try {
                $this->di['events_manager']->fire(array('event' => 'onBeforeAdminOrderActivate', 'params' => array('id' => $addon->id)));
                $this->createFromOrder($addon);
                $this->di['events_manager']->fire(array('event' => 'onAfterAdminOrderActivate', 'params' => array('id' => $addon->id)));
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }*/
        return true;
    }
    public function activateOrder(\Model_ClientOrder $order, $data = array())
    {
        $event_params = array('id' => $order->id);
        $this->di['events_manager']->fire(array('event' => 'onBeforeAdminOrderActivate', 'params' => $event_params));
        $result = $this->createFromOrder($order);

        if (is_array($result)) {
            $event_params = array_merge($event_params, $result);
        }
        $this->di['events_manager']->fire(array('event' => 'onAfterAdminOrderActivate', 'params' => $event_params));
        //$this->activateOrderAddons($order);
        $this->di['logger']->info('Activated order #%s', $order->id);
        return TRUE;
    }
    public function createFromOrder(\Model_ClientOrder $order)
    {
        /*$service = $this->getOrderService($order);
        if (!is_object($service)) {
            $mod = $this->di['mod']('service' . $order->service_type);
            $s   = $mod->getService();
            if (method_exists($s, 'create') || method_exists($s, 'action_create')) {

                $service = $this->_callOnService($order, \Model_ClientOrder::ACTION_CREATE);
                if (!is_object($service)) {
                    throw new \Box_Exception('Error creating ' . $order->service_type . ' service for order ' . $order->id);
                }

                $order->service_id = $service->id;
                $order->updated_at = date('Y-m-d H:i:s');
                $resp = $this->di['db']->store($order);
            }
        }

        try {
            $result = $this->_callOnService($order, \Model_ClientOrder::ACTION_ACTIVATE);
        } catch (\Exception $e) {
            $order->status = \Model_ClientOrder::STATUS_FAILED_SETUP;
            $this->di['db']->store($order);

            $this->saveStatusChange($order, $e->getMessage());
            $this->di['logger']->info('Aprece un error  #%s', '');
            throw $e;
        }

        // set automatic order expiration
        if (!empty($order->period)) {
            $from_time = (NULL === $order->expires_at) ? time() : strtotime($order->expires_at);

            $period            = $this->di['period']($order->period);
            $order->expires_at = date('Y-m-d H:i:s', $period->getExpirationTime($from_time));
        }

        $order->status       = \Model_ClientOrder::STATUS_ACTIVE;
        $order->activated_at = date('Y-m-d H:i:s');
        $order->suspended_at = NULL;
        $order->canceled_at  = NULL;
        $order->updated_at   = date('Y-m-d H:i:s');
        $this->di['db']->store($order);

        $productModel = $this->di['db']->load('Product', $order->product_id);
        if($productModel instanceof \Model_Product) {
            $this->stockSale($productModel, $order->quantity);
        } else {
            error_log(sprintf('Order without product ID detected Order #%s', $order->id));
        }

        $this->saveStatusChange($order, 'Order activated');*/

        //return $result;
    }
    public function impServers($servers){
        foreach($servers as $server){
            $sql = "SELECT COUNT(id) as counter FROM service_hosting_server WHERE ip = '{$server->ipaddress}' AND hostname = '{$server->hostname}'";
            $data = $this->di['db']->getRow($sql);
            if($data['counter']==0){
                $server->assigned_ips ='';
                $server->status_url ='';
                $server->max_accounts =$server->maxAllowedServices;
                $server->ns1 ='';
                $server->ns2 ='';
                $server->ns3 ='';
                $server->ns4 ='';
                $server->username ='root';
                $server->password ='123456';
                $server->accesshash ='';
                $server->port ='';
                $server->secure =0;
                $module =($server->module=='cwp7')?'Cwp':$server->module;
                $this->createServer($server->name,$server->ipaddress,$module,$server);
            }else{
                $this->di['logger']->info('duplicate record #%s', $server->hostname);
            }
        }
    }
    public function createServer($name, $ip, $manager, $data)
    {
        $arrData = array();
        $arrData['hostname']=$data->hostname;
        $arrData['assigned_ips']=$data->assigned_ips;
        $arrData['active']=$data->active;
        $arrData['status_url']=$data->status_url;
        $arrData['max_accounts']=$data->max_accounts;
        $arrData['ns1']=$data->ns1;
        $arrData['ns2']=$data->ns2;
        $arrData['ns3']=$data->ns3;
        $arrData['ns4']=$data->ns4;
        $arrData['username']=$data->username;
        $arrData['password']=$data->password;
        $arrData['accesshash']=$data->accesshash;
        $arrData['port']=$data->port;
        $arrData['secure']=$data->secure;

        $model = $this->di['db']->dispense('ServiceHostingServer');
        $model->name = $name;
        $model->ip = $ip;

        $model->hostname     = $this->di['array_get']($arrData, 'hostname');
        $model->assigned_ips = $this->di['array_get']($arrData, 'assigned_ips');
        $model->active       = $this->di['array_get']($arrData, 'active', 1);
        $model->status_url   = $this->di['array_get']($arrData, 'status_url');
        $model->max_accounts = $this->di['array_get']($arrData, 'max_accounts');

        $model->ns1 = $this->di['array_get']($arrData, 'ns1');
        $model->ns2 = $this->di['array_get']($arrData, 'ns2');
        $model->ns3 = $this->di['array_get']($arrData, 'ns3');
        $model->ns4 = $this->di['array_get']($arrData, 'ns4');

        $model->manager    = $manager;
        $model->username   = $this->di['array_get']($arrData, 'username');
        $model->password   = $this->di['array_get']($arrData, 'password');
        $model->accesshash = $this->di['array_get']($arrData, 'accesshash');
        $model->port       = $this->di['array_get']($arrData, 'port');
        //$model->secure     = $this->di['array_get']($arrData, 'secure', 0);

        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');

        $newId             = $this->di['db']->store($model);

        $this->di['logger']->info('Added new hosting server %s', $newId);

        return $newId;
    }
    public function createHp($name, $data,$product,$idproduct)
    {
        $model = $this->di['db']->dispense('ServiceHostingHp');
        $model->name = $name;

        $model->bandwidth = $this->di['array_get']($data, 'bandwidth', 1024 * 1024);
        $model->quota     = $this->di['array_get']($data, 'quota', 1024 * 1024);

        $model->max_addon = $this->di['array_get']($data, 'max_addon', 1);
        $model->max_park  = $this->di['array_get']($data, 'max_park', 1);
        $model->max_sub   = $this->di['array_get']($data, 'max_sub', 1);
        $model->max_pop   = $this->di['array_get']($data, 'max_pop', 1);
        $model->max_sql   = $this->di['array_get']($data, 'max_sql', 1);
        $model->max_ftp   = $this->di['array_get']($data, 'max_ftp', 1);

        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $newId = $this->di['db']->store($model);
        if(is_numeric($newId)){
            $this->di['logger']->info('Added new hosting plan %s', $newId);
            $this->updateProdRelationPackageServer($newId,$idproduct);
            return $newId;
        }else{
            $this->di['logger']->info('Se genero un error al tratar de crear un paquete %s', $newId);
        }
    }
    public function updateProdRelationPackageServer($idPack,$idproduct){
        $data = $this->di['db']->getRow("SELECT id FROM service_hosting_server WHERE id > 0 ORDER BY id LIMIT 0,1");
        if(isset($data['id'])){
            $config = array('server_id'=>$data['id'],'hosting_plan_id'=>$idPack,'reseller'=>0,'free_domain'=>0,'free_transfer'=>0);
            $jsonConfig = json_encode($config);
            $resp = $this->di['db']->exec("UPDATE product SET config = '{$jsonConfig}' WHERE id = :idproduct", array(':idproduct' => $idproduct));
            $this->di['logger']->info('Actualizacion de Configuracion de Producto %s', $resp);
        }else{
            $this->di['logger']->info('Ni existe servidores creados %s', '');
        }
    }
    public function _callOnService(\Model_ClientOrder $order, $action)
    {
        $repo = $this->di['mod_service']('service' . $order->service_type);
        //@deprecated
        //@todo remove this when doctrine is removed
        $core_services = array(
            \Model_ProductTable::CUSTOM,
            \Model_ProductTable::LICENSE,
            \Model_ProductTable::DOWNLOADABLE,
            \Model_ProductTable::HOSTING,
            \Model_ProductTable::MEMBERSHIP,
            \Model_ProductTable::DOMAIN,
            //\Model_ProductTable::SERVER,
        );

        if (in_array($order->service_type, $core_services)) {

            $m = 'action_' . $action;

            if (!method_exists($repo, $m) || !is_callable(array($repo, $m))) {
                throw new \Box_Exception('Service ' . $order->service_type . ' do not support ' . $m);
            }
            return $repo->$m($order);
        } else {

            //@new logic for services
            $o       = $this->di['db']->findOne('client_order',
                'id = :id',
                array(':id' => $order->id));
            $service = NULL;
            $sdbname = 'service_' . $order->service_type;
            if ($order->service_id) {
                $service = $this->di['db']->load($sdbname, $order->service_id);
            }
            if (method_exists($repo, $action) && is_callable(array($repo, $action))) {
                return $repo->$action($o, $service);
            }
        }
        error_log(sprintf('Service %s does not support action %s', $order->service_type, $action));

        return null;
    }
    public function saveStatusChange(\Model_ClientOrder $order, $notes = NULL)
    {
        $os                  = $this->di['db']->dispense('ClientOrderStatus');
        $os->client_order_id = $order->id;
        $os->status          = $order->status;
        $os->notes           = $notes;
        $os->created_at      = date('Y-m-d H:i:s');
        $os->updated_at      = date('Y-m-d H:i:s');
        $this->di['db']->store($os);
    }
    public function getOrderService(\Model_ClientOrder $order)
    {
        if (NULL !== $order->service_id) {
            //@deprecated
            //@todo remove this when doctrine is removed
            $core_services = array(
                \Model_ProductTable::CUSTOM,
                \Model_ProductTable::LICENSE,
                \Model_ProductTable::DOWNLOADABLE,
                \Model_ProductTable::HOSTING,
                \Model_ProductTable::MEMBERSHIP,
                \Model_ProductTable::DOMAIN,
            );
            if (in_array($order->service_type, $core_services)) {
                $repo_class = $this->_getServiceClassName($order);

                return $this->di['db']->load($repo_class, $order->service_id);
            } else {
                $service = $this->di['db']->findOne('service_' . $order->service_type,
                    'id = :id',
                    array(':id' => $order->service_id));

                return $service;
            }
        }

        return NULL;
    }
    protected function _getServiceClassName(\Model_ClientOrder $order)
    {
        $s = $this->di['tools']->to_camel_case($order->service_type, true);

        return 'Service' . ucfirst($s);
    }
    public function action_create(\Model_ClientOrder $order)
    {
        $orderService = $this->di['mod_service']('order');
        $c = $orderService->getConfig($order);
        //$this->validateOrderData($c);

        $server = $this->di['db']->getExistingModelById('ServiceHostingServer', $c['server_id'], 'Server from order configuration was not found');

        $hp = $this->di['db']->getExistingModelById('ServiceHostingHp', $c['hosting_plan_id'], 'Hosting plan from order configuration was not found');

        $model = $this->di['db']->dispense('ServiceHosting');
        $model->client_id = $order->client_id;
        $model->service_hosting_server_id = $server->id;
        $model->service_hosting_hp_id = $hp->id;
        $model->sld = $c['sld'];
        $model->tld = $c['tld'];
        $model->ip = $server->ip;
        $model->reseller = $this->di['array_get']($c, 'reseller', false);
        $model->created_at = date('Y-m-d H:i:s');
        $model->updated_at = date('Y-m-d H:i:s');
        $this->di['db']->store($model);

        return $model;
    }
    public function validateOrderData(array &$data)
    {
        /*if(!isset($data['server_id'])) {
            throw new \Box_Exception('Hosting product is not configured completely. Configure server for hosting product.', null, 701);
        }*/
        /*print_r($data); die;
        if(!isset($data['hosting_plan_id'])) {
            throw new \Box_Exception('Hosting product is not configured completely. Configure hosting plan for hosting product.', null, 702);
        }*/
        if(!isset($data['sld']) || empty($data['sld'])) {
            throw new \Box_Exception('Domain name is not valid.', null, 703);
        }
        if(!isset($data['tld']) || empty($data['tld'])) {
            throw new \Box_Exception('Domain extension is not valid.', null, 704);
        }
    }
    public function migrationSupport($data,$apiWhmcs){
        $this->apiWhmcs = $apiWhmcs;
        $this->validateTableSupport();
        if(isset($data->supportDepartments)){
            $dptoSupport = $data->supportDepartments->departments->department;
            $relId=array();
            foreach($dptoSupport as $department){
                if($department->id !=1){
                    $relId[$department->id]=$this->createGroupSupport(trim($department->name));
                }
            }
        }
        if(isset($data->adminUsers)){
            $adminUsers = $data->adminUsers->admin_users;
            foreach($adminUsers as $users){
                $rol = ($users->roleId ==1)?'admin':'support';
                $admin_group = ($users->roleId ==1)?1:2;
                $datos = array('rol'=>$rol,'admin_group'=>$admin_group,'email'=>$users->email,'name'=>$users->fullName,'signature'=>$users->signature,'password'=>rand());
                $adminId[$users->id]=$this->createAdmin($datos);
            }
        }
        if(isset($data->tickets)){
            $tick = $data->tickets->tickets->ticket;
            $i=0;
            foreach($tick as $ticket){
                $this->createTicketSupport($ticket);
               $i++;
               //if($i==5){echo 'Ya fueron 5'; die;}
            }
        }
        if(isset($data->custom_fields)){

            $this->migrateCustomsFields($data->custom_fields);
        }

    }
    public function createGroupSupport($name)
    {
        $data = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM support_helpdesk WHERE name = '{$name}'");
        if($data['counter']==0){
            $model              = $this->di['db']->dispense('SupportHelpdesk');
            $model->name        = $name;
            $model->email       = "{$name}@yourdomain.com";
            $model->can_reopen  = $this->di['array_get']($data, 'can_reopen', NULL);
            $model->close_after = $this->di['array_get']($data, 'close_after', NULL);
            $model->signature   = $this->di['array_get']($data, 'signature', NULL);
            $model->created_at  = date('Y-m-d H:i:s');
            $model->updated_at  = date('Y-m-d H:i:s');
            $id                 = $this->di['db']->store($model);

            if(is_numeric($id)){
                $this->di['logger']->info('A new support group has been created %s', $id);
                return (int) $id;
            }else{
                $this->di['logger']->info('Something went wrong trying to create the support group %s', $id);
            }
        }else{
            $this->di['logger']->info('Duplicate record %s', $name);
        }
    }
    public function createAdmin(array $data)
    {
        if(isset($data['email']) && !empty($data['email'])){
            $daResp = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM `admin` WHERE email = '{$data['email']}'");
            if($daResp['counter']==0){
                $systemService = $this->di['mod_service']('system');
                $systemService->checkLimits('Model_Admin', 3);

                $data['status'] = 'active';
                $admin = $this->di['db']->dispense('Admin');
                $admin->role = $data['rol'];
                $admin->admin_group_id = $this->di['array_get']($data, 'admin_group', NULL);
                $admin->name =$this->di['array_get']($data, 'name', NULL);
                $admin->email = $this->di['array_get']($data, 'email', NULL);
                $admin->pass = $this->di['password']->hashIt($data['password']);
                $admin->protected = 0;
                $admin->status = $admin->getStatus($data['status']);
                $admin->created_at = date('Y-m-d H:i:s');
                $admin->updated_at = date('Y-m-d H:i:s');

                $newId = $this->di['db']->store($admin);

                $this->di['logger']->info('Main administrator %s account created', $admin->email);
                $this->_sendMail($admin, $data['password']);

                return $newId;
            }else{
                $this->di['logger']->info('Duplicate record %s', $data['email']);
            }
        }else{
            $this->di['logger']->info('Empty Register %s', $data['email']);
        }
    }
    protected function _sendMail($admin, $admin_pass)
    {
        $admin_name = $admin->name;
        $admin_email = $admin->email;

        $client_url = $this->di['url']->link('/');
        $admin_url = $this->di['url']->adminLink('/');

        $content = "Hi $admin_name, ".PHP_EOL;
        $content .= "You have successfully setup BoxBilling at ".BB_URL.PHP_EOL;
        $content .= "Access client area at: ".$client_url.PHP_EOL;
        $content .= "Access admin area at: ".$admin_url." with login details:".PHP_EOL;
        $content .= "Email: ".$admin_email.PHP_EOL;
        $content .= "Password: ".$admin_pass.PHP_EOL.PHP_EOL;

        $content .= "Read BoxBilling documentation to get started http://docs.boxbilling.com/".PHP_EOL;
        $content .= "Thank You for using BoxBilling.".PHP_EOL;

        $subject = sprintf('BoxBilling is ready at "%s"', BB_URL);

        $systemService =  $this->di['mod_service']('system');
        $from = $systemService->getParamValue('company_email');
        $emailService = $this->di['mod_service']('Email');
        $emailService->sendMail($admin_email, $from, $subject, $content);
    }
    public function createTicketSupport($ticket){
        $respCli = $this->di['db']->getRow("SELECT id FROM client WHERE email = '{$ticket->email}'");
        $client_id = $respCli['id'];
        $daResp = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM support_ticket WHERE client_id = '{$client_id}' AND subject ='{$ticket->subject} | {$ticket->tid}'");
        if($daResp['counter']==0){
            $idDpt                      = $this->idDptoSupport($ticket->deptname);
            $status                     = array('Closed'=>'closed','On_hold'=>'on_hold','Open'=>'open');
            $model                      = $this->di['db']->dispense('SupportTicket');
            $model->support_helpdesk_id = $idDpt;
            $model->client_id           = $client_id;
            $model->priority            = 100;
            $model->subject             = "{$ticket->subject} | {$ticket->tid}";
            $model->status              = $status[$ticket->status];
            $model->created_at          = $ticket->date;
            $model->updated_at          = date('Y-m-d H:i:s');
            $id                         = $this->di['db']->store($model);
            if(is_numeric($id)){
                $this->di['logger']->info('Added  ticket #%s', $id);
                $this->messagesTicket($client_id,$id,$ticket);
                return $id;
            }else{
                $this->di['logger']->info('An error occurred %s', $id);
            }
        }else{
            $this->di['logger']->info('Duplicate record %s', "{$ticket->subject} | {$ticket->tid}");
        }
    }
    public function idDptoSupport($name){
        $resp = $this->di['db']->getRow("SELECT id FROM support_helpdesk WHERE name = '{$name}'");
        return (!is_numeric(trim($resp['id'])))?1:trim($resp['id']);
    }
    public function messagesTicket($client_id,$idbb,$ticket){
        $postfields = array(
            'identifier' => $this->apiWhmcs->identifier,
            'secret' => $this->apiWhmcs->secret,
            'action' => 'GetTicket',
            'responsetype' => 'json',
            'ticketnum' => $ticket->tid,
        );
        $mesages=$this->get_curl($this->apiWhmcs,$postfields);
        if(isset($mesages->replies->reply)){
            $bolets = $mesages->replies->reply;
            $notes = (isset($mesages->notes->note))?$mesages->notes->note : '';
            foreach($bolets as $bolet){
                if(trim($bolet->requestor_type)=='Owner'){
                    $admin_id=null;
                    $client=$client_id;
                }else{
                    $idAdmn=$this->idOperator(trim($bolet->requestor_name));
                    $admin_id=(!is_numeric($idAdmn))?1:$idAdmn;
                    $client=null;
                }
                $model                    = $this->di['db']->dispense('SupportTicketMessage');
                $model->support_ticket_id = $idbb;
                $model->client_id         = $client;
                $model->admin_id          = $admin_id;
                $model->content           = $bolet->message;
                $model->attachment        = substr($bolet->attachment,0,250);
                $model->created_at        = $bolet->date;
                $model->updated_at        = date('Y-m-d H:i:s');
                $idMsj                    = $this->di['db']->store($model);

                if(is_numeric($idMsj)){
                    if($notes!=''){
                        $modelNote  = $this->di['db']->dispense('SupportTicketNote');
                        foreach ($notes as $note){
                            $modelNote->support_ticket_id    =$idbb;
                            $modelNote->admin_id            =$this->idOperator(trim($note->admin));
                            $modelNote->note                = trim($note->message);
                            $modelNote->created_at          = $note->date;
                            $modelNote->updated_at          = date('Y-m-d H:i:s');
                            $idNote                         = $this->di['db']->store($modelNote);
                        }
                    }
                    $postfields = array(
                        'identifier' => $this->apiWhmcs->identifier,
                        'secret' => $this->apiWhmcs->secret,
                        'action' => 'GetTicketAttachment',
                        'responsetype' => 'json',
                        'relatedid' => $ticket->tid,
                        'type' => '', //ticket, reply, note
                        'index' =>0,
                    );
                    $fileTicket=$this->get_curl($this->apiWhmcs,$postfields);

                }else{
                    $this->di['logger']->info('An error occurred while trying to register the ticket message %s', '');
                }
            }
        }
    }
    public function GetTicketAttachment($apiWhmcs,$postfields,$data){

    }
    public function idOperator($name){
        $resp = $this->di['db']->getRow("SELECT id FROM `admin` WHERE name = '{$name}'");
        return (!is_numeric(trim($resp['id'])))?1:trim($resp['id']);
    }
    public function get_curl($apiWhmcs,$postfields){
        // Call the API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiWhmcs->url . 'includes/api.php');
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
    public function validateTableSupport(){
        $q = "CREATE TABLE IF NOT EXISTS `customfields` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `type` varchar(255) NOT NULL,
          `relid` int(11) NOT NULL,
          `fieldname` varchar(255) NOT NULL,
          `descripcion`  varchar(255) NOT NULL,
          `fieldoptions` varchar(255) DEFAULT NULL,
          `adminonly` varchar(255) DEFAULT NULL,
          `required` int(11) DEFAULT NULL,
          `encryption` int(11) DEFAULT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $resp = $this->di['db']->exec($q);

        $q = "CREATE TABLE IF NOT EXISTS `customfieldsvalues` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `fieldid` int(11) NOT NULL,
          `relid` int(11) NOT NULL,
          `value` varchar(255) NOT NULL,
          `created_at` datetime NOT NULL,
          `updated_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $resp = $this->di['db']->exec($q);

    }
    public function migrateCustomsFields($data){
        foreach($data as $reg){
            $marke = $reg->id_producto->tid;
            $infoTicket = (!isset($this->$marke))?$this->infoTicket($marke):$this->$marke;
            //print_r($infoTicket);
            //print_r($reg);
        }
        die;
    }
    public function infoTicket($marke){
        $sel="SELECT * FROM support_ticket WHERE support_ticket.`subject` LIKE '%| {$marke}'";
        $resp = $this->di['db']->getAll($sel);
        $this->$marke = $resp;
    }
    public function passNewUser($email, $pass){

    }
    public function migrationOrdens($email,$GetOrdens){
        $datas = $GetOrdens->orders->order;
        $client_id=$this->searchIdClient($email);
        // falta validar que no este duplicado
        foreach($datas as $data){
            $currency = $data->currencysuffix;
            //$this->di['events_manager']->fire(array('event' => 'onBeforeAdminOrderCreate', 'params' => $data, 'subject' => $product->type));
            $arrPed = array('Annually'=>'1Y','Monthly'=>'1M');
            $qyarr  = array('Annually'=>1,'Monthly'=>1);
            $arrSt = array('Suspended'=>'suspended','Active'=>'active','Canceled'=>'canceled');
            $period = $arrPed[$data->billingcycle];
            $order  = $this->di['db']->dispense('ClientOrder');

            if(isset($data->lineitems->lineitem)){
                $details = $data->lineitems->lineitem;
                if(count($details)>0){
                    foreach($details as $detail){
                        if($detail->type =='product'){
                            $tz = explode(" - ",$detail->product);
                            $key = count($tz)-1;
                            $nameProd = $tz[$key];
                            $id_Product = $this->searchIdProduct(trim($nameProd));
                            $qty            = $qyarr[$detail->billingcycle];
                            // {"server_id":"2","hosting_plan_id":"1","reseller":"0","free_domain":"0","free_transfer":"0","import":"0","domain":{"owndomain_sld":"midominio2","owndomain_tld":".com","action":"owndomain"},"username":"user2","password":"12121212","period":"1Y","sld":"midominio2","tld":".com"}
                            $config         =  array(
                                'server_id'=>'',
                                'hosting_plan_id'=>'',
                                'reseller'=>'',
                                'free_domain'=>'',
                                'free_transfer'=>'',
                                'import'=>'',
                                'domain'=>array(
                                    'owndomain_sld'=>'',
                                    'owndomain_tld'=>'',
                                    'action'=>'',
                                ),
                                'username'=>'',
                                'password'=>'',
                                'period'=>'',
                                'sld'=>'',
                                'tld'=>'',
                                );
                            $order->title          = "{$nameProd} {$detail->domain}";
                        }elseif($detail->type =='domain'){
                            $id_Product =1;
                            // {"register_sld":"dominio6","register_tld":".com","register_years":"5","ns1":"","ns2":"","ns3":"","ns4":"","transfer_sld":"","transfer_tld":".com","transfer_code":"","action":"register","period":"5Y","quantity":5,"id":"3","product_id":"1","form_id":null,"title":"Domain dominio6.com registration","type":"domain","unit":"year","price":"11.99","setup_price":0,"discount":0,"discount_price":0,"discount_setup":0,"total":59.95,"sld":"","domain":{"owndomain_sld":"","register_sld":"","transfer_sld":""}}
                            $config         = array(
                                'register_sld'=>'',
                                'register_tld'=>'',
                                'register_years'=>'',
                                'ns1'=>'',
                                'ns2'=>'',
                                'ns3'=>'',
                                'ns4'=>'',
                                'transfer_sld'=>'',
                                'transfer_tld'=>'',
                                'transfer_code'=>'',
                                'action'=>'',
                                'period'=>'',
                                'quantity'=>'',
                                'id'=>'',
                                'product_id'=>1,
                                'form_id'=>'null',
                                'title'=>'',
                                'type'=>'domain',
                                'unit'=>'',
                                'price'=>'',
                                'setup_price'=>'',
                                'discount'=>'',
                                'discount_price'=>'',
                                'discount_setup'=>'',
                                'total'=>'',
                                'sld'=>'',
                                'domain'=>array(
                                    'owndomain_sld'=>'',
                                    'register_sld'=>'',
                                    'transfer_sld'=>'',
                                ),
                            );
                            $order->title   = "{$detail->producttype}  {$detail->domain} {$detail->product}";
                            $qty            = $qyarr[$data->billingcycle];
                        }
                        if(is_numeric($id_Product)){
                            $activate       = ($data->status =='Active')?true:false;
                            $invoiceOption  = 'no-invoice';

                            $order->client_id      = $client_id;
                            $order->product_id     = $id_Product;
                            $order->group_id       = uniqid();
                            $order->group_master   = 1;

                            $order->currency       = $currency;
                            $order->quantity       = $qty;
                            $order->service_type   = ($detail->type=='product')?'hosting':'domain';
                            $order->unit           = ($detail->type=='domain')?'year':'product';
                            $order->status         = (isset($arrSt[$detail->status]))?$arrSt[$detail->status]:'pending_setup';
                            $order->config         = json_encode($config);
                            $order->invoice_option = $invoiceOption;
                            $order->price          = str_replace($currency,'',$detail->amount);
                            $order->notes          = 'Num. Orden Whmcs '.$data->ordernum;
                            $order->created_at = $data->date;
                            $order->updated_at = date('Y-m-d H:i:s');

                            $id = $this->di['db']->store($order);

                            /*if (isset($data['meta']) && is_array($data['meta'])) {
                                foreach ($data['meta'] as $k => $v) {
                                    $mm = $this->di['db']->findOne('client_order_meta', 'client_order_id = :id AND name = :n', array(':id' => $order->id, ':n' => $k));
                                    if (!$mm) {
                                        $mm                  = $this->di['db']->dispense('ClientOrderMeta');
                                        $mm->client_order_id = $id;
                                        $mm->name            = $k;
                                        $mm->created_at      = date('Y-m-d H:i:s');
                                    }
                                    $mm->value      = $v;
                                    $mm->updated_at = date('Y-m-d H:i:s');
                                    $this->di['db']->store($mm);
                                }
                            }*/

                            //$this->di['events_manager']->fire(array('event' => 'onAfterAdminOrderCreate', 'params' => array('id' => $order->id), 'subject' => $product->type));

                            $this->di['logger']->info('Created order #%s', $id);

                            // invoice options
                            /*if ($invoiceOption == 'issue-invoice' && $order->price > 0) {
                                $invoiceService = $this->di['mod_service']('invoice');
                                $invoice        = $invoiceService->generateForOrder($order);

                                $invoiceService->approveInvoice($invoice, array('id' => $invoice->id, 'use_credits' => true));
                            }*/

                            // activate imediately if say so
                            if ($activate) {
                                try {
                                    $this->activateOrder($order);
                                } catch (\Exception $e) {
                                    error_log($e->getMessage());
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function searchIdProduct($name){
        $row = $this->di['db']->getRow("SELECT id FROM product WHERE title = '{$name}'");
        return $row['id'];
    }
    public function searchIdClient($email,$field ='id'){
        $row = $this->di['db']->getRow("SELECT {$field} FROM client WHERE email = '{$email}'");
        return($field=='id')?$row['id']:$row;
    }
    public function migrationInvoices($client,$Getinvoices,$apiWhmcs){
        $systemService = $this->di['mod_service']('system');
        $seller = $systemService->getCompany();
        $dataInvoice = $Getinvoices->invoices->invoice;
        foreach($dataInvoice as $Getinvoice){
            if($this->validateInvoice($Getinvoice->id)==0){
                $invoice  = $this->di['db']->dispense('Invoice');
                $user = $this->searchIdClient($client->email,'*');

                $invoice->seller_company  = $seller['name'];
                $invoice->seller_company_vat  = $seller['vat_number'];
                $invoice->seller_company_number  = $seller['number'];
                $invoice->seller_address  = trim($seller['address_1'] .' '. $seller['address_2'] .' '. $seller['address_3']);
                $invoice->seller_phone    = $seller['tel'];
                $invoice->seller_email    = $seller['email'];
                $invoice->nr    = $Getinvoice->id;
                $invoice->serie    = 'BOX';
                $invoice->hash    = sha1(uniqid());
                $invoice->client_id = $user['id'];
                $invoice->status = strtolower($Getinvoice->status);
                $invoice->currency = $Getinvoice->currencycode;
                $invoice->buyer_first_name = $user['first_name'];
                $invoice->buyer_last_name = $user['last_name'];
                $invoice->buyer_company = $user['company'];
                $invoice->buyer_address = $user['last_name'];
                $invoice->buyer_city = $user['city'];
                $invoice->buyer_state = $user['state'];
                $invoice->buyer_country = $user['country'];
                $invoice->buyer_zip = $user['postcode'];
                $invoice->buyer_phone = $user['phone'];
                $invoice->buyer_email = $user['email'];
                $invoice->gateway_id = 1;
                $invoice->approved = 1;
                $invoice->taxname = '';
                $invoice->taxrate = '';
                $invoice->due_at = $Getinvoice->date;
                $invoice->paid_at = $Getinvoice->datepaid;
                $invoice->created_at = $Getinvoice->date;
                $invoice->updated_at = $Getinvoice->duedate;
                $tax1 = $Getinvoice->taxrate;
                $tax2 = $Getinvoice->taxrate2;

                $postfieldsCliente = array(
                    'identifier' => $apiWhmcs->identifier,
                    'secret' => $apiWhmcs->secret,
                    'action' => 'GetInvoice',
                    'invoiceid' =>$client->id,
                    'responsetype' => 'json',
                );
                $Getinvoice = $this->get_curl($apiWhmcs,$postfieldsCliente);
                if(isset($Getinvoice->result) && $Getinvoice->result=='success'){
                    $idInvoice = $this->di['db']->store($invoice);
                    if(is_numeric($idInvoice)){
                        $detailInvoices = $Getinvoice->items->item;
                        if(isset($Getinvoice->items->item)){
                            foreach($detailInvoices as $det){
                                $detail = $this->di['db']->dispense('InvoiceItem');
                                $detail->invoice_id     = $idInvoice;
                                $detail->type           = 'order';
                                $detail->rel_id         = $this->lastrelId();
                                $detail->task           = '';
                                $detail->status         = ($Getinvoice->status=='Unpaid')?'pending_payment':'paid';
                                $detail->title          = $det->description;
                                $detail->period         = '1Y';
                                $detail->quantity       = '1';
                                $detail->unit           = 'type';
                                $detail->charged        = 0;
                                $detail->price          = $det->amount;
                                $detail->taxed          = $det->taxed;
                                $detail->created_at     = $Getinvoice->date;
                                $detail->updated_at     = $Getinvoice->duedate;
                                $resp = $this->di['db']->store($detail);
                            }
                        }
                    }
                }
            }
        }
    }
    public function validateInvoice($nr){
        $daResp = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM invoice WHERE nr = '$nr'");
        return ($daResp['counter']==0)?0:1;
    }
    public function lastrelId(){
        $daResp = $this->di['db']->getRow("SELECT rel_id FROM invoice_item ORDER BY invoice_item.rel_id DESC LIMIT 0,1");
        return ($daResp['rel_id']+1);
    }
    function split_domain($host,$SLDs)
    {
        $parts=explode('.',$host);
        $index=count($parts)-1;
        if($index>0 && in_array($parts[$index-1],$SLDs)) $index--;
        if($index===0) $index++;
        $subdomain=implode('.',array_slice($parts,0,$index-1));
        $domain=$parts[$index-1];
        $tld=implode('.',array_slice($parts,$index));
        return (object)['subdomain'=>$subdomain,'domain'=>$domain,'tls'=>$tld];
    }
    public function InfoHost($ip){
        $data = $this->di['db']->getRow("SELECT * FROM service_hosting_server WHERE ip = '$ip'");
        $resp =[];
        $resp->ip = $data['ip'];
        $resp->name = $data['name'];
        $resp->hostname = $data['hostname'];
        $resp->id = $data['id'];
        return $resp;
    }
    public function InfoServiceHosting($datServ){
        $data = $this->di['db']->getRow("SELECT COUNT(id) as counter FROM service_hosting WHERE client_id = {$datServ['client_id']} AND service_hosting_hp_id ={$datServ['service_hosting_hp_id']} AND username='{$datServ['username']}' AND ip='{$datServ['ip']}'");
        if($data['counter']==0) {
            $model = $this->di['db']->dispense('ServiceHosting');
            $model->client_id                   =   $datServ['client_id'];
            $model->service_hosting_server_id   =   $datServ['service_hosting_server_id'];
            $model->service_hosting_hp_id       =   $datServ['service_hosting_hp_id'];
            $model->sld                         =   $datServ['sld'];
            $model->tld                         =   $datServ['tld'];
            $model->ip                          =   $datServ['ip'];
            $model->username                    =   $datServ['username'];
            $model->pass                        =   $datServ['pass'];
            $model->reseller                    =   $datServ['reseller'];
            $id                                 =   $this->di['db']->store($model);
            return $id;
        }else{
            return false;
        }
    }
}
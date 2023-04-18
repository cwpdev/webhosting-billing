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
 * This file connects BoxBilling admin area interface and API
 * Class does not extend any other class
 */

namespace Box\Mod\Whmcsimp\Controller;

class Admin implements \Box\InjectionAwareInterface
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

    /**
     * This method registers menu items in admin area navigation block
     * This navigation is cached in bb-data/cache/{hash}. To see changes please
     * remove the file
     *
     * @return array
     */
    public function fetchNavigation()
    {
        return array(
            'group'  =>  array(
                'index'     => 901,                // menu sort order
                'location'  =>  'whmcsimp',          // menu group identificator for subitems
                'label'     => 'Migration Whmcs',    // menu group title
                'class'     => '',           // used for css styling menu item
            ),
            'subpages'=> array(
                array(
                    'location'  => 'whmcsimp', // place this module in extensions group
                    'label'     => 'Migration Module',
                    'index'     => 100,
                    'uri'       => $this->di['url']->adminLink('/whmcsimp'),
                    'class'     => 'fe fe-download',
                ),

            ),
        );
    }

    /**
     * Methods maps admin areas urls to corresponding methods
     * Always use your module prefix to avoid conflicts with other modules
     * in future
     *
     *
     * @whmcsimp $app->get('/whmcsimp/test',      'get_test', null, get_class($this)); // calls get_test method on this class
     * @whmcsimp $app->get('/whmcsimp/:id',        'get_index', array('id'=>'[0-9]+'), get_class($this));
     * @param \Box_App $app
     */
    public function register(\Box_App &$app)
    {
        $app->get('/whmcsimp',             'get_index', array(), get_class($this));
        $app->post('/whmcsimp/ajax', 'get_ajax', array(), get_class($this));
        $app->get('/whmcsimp/getConfig',        'getConfig', array(), get_class($this));
        /*$app->get('/whmcsimp/user/:id',    'get_user', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/whmcsimp/api',         'get_api', array(), get_class($this));*/
    }
    public function get_index(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];
        /*$sql = 'SELECT * FROM whmcsimport WHERE id = 1';
        $rows   = $this->di['db']->getAll($sql);
        $result=array();
        $result['url']  = 'wwwwwwwwwwwwwwwwwwwwww';
        $result['identifier']  = $rows['identifier'];
        $result['secret']  = $rows['secret'];
        $params =$result;*/
        return $app->render('mod_whmcsimp_index');
    }
    public function get_test(\Box_App $app)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

        $params = array();
        $params['youparamname'] = 'yourparamvalue';

        return $app->render('mod_whmcsimp_index', $params);
    }
    public function get_ajax(\Box_App $app){

       /* $api = $this->di['api_admin'];
        $product = $api->product_get(array('id' => $this->di['request']->getPost('data')));
        $client = $api->client_get(array('id' => $this->di['request']->getPost('client_id')));
        return $product;*/
        //return $app->render('mod_order_new', array('product' => $product, 'client' => $client));

    }
    public function get_user(\Box_App $app, $id)
    {
        // always call this method to validate if admin is logged in
        $this->di['is_admin_logged'];

        $params = array();
        $params['userid'] = $id;
        return $app->render('mod_whmcsimp_index', $params);
    }
    public function get_api(\Box_App $app, $id = null)
    {
        // always call this method to validate if admin is logged in
        $api = $this->di['api_admin'];
        $list_from_controller = $api->whmcsimp_get_something();

        $params = array();
        $params['api_whmcsimp'] = true;
        $params['list_from_controller'] = $list_from_controller;

        return $app->render('mod_whmcsimp_index', $params);
    }
}

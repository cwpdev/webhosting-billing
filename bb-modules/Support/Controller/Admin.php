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


namespace Box\Mod\Support\Controller;

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

    public function fetchNavigation()
    {
        return array(
            'group'  =>  array(
                'location'  => 'support',
                'index'     => 500,
                'label' => 'Support',
                'class' => 'support',
                'sprite_class' => 'dark-sprite-icon sprite-dialog',
            ),
            'subpages' => array(
                array(
                    'location'  => 'support',
                    'label' => 'Client tickets',
                    'uri' => $this->di['url']->adminLink('support', array('status' => 'open')),
                    'index'     => 100,
                    'class'     => 'fe fe-headphones',
                ),
                /*array(
                    'location'  => 'support',
                    'label' => 'Advanced ticket search',
                    'uri' => $this->di['url']->adminLink('support', array('show_filter' => 1)),
                    'index'     => 200,
                    'class'     => 'fe fe-search',
                ),*/
                array(
                    'location'  => 'support',
                    'label' => 'Public tickets',
                    'uri' => $this->di['url']->adminLink('support/public-tickets', array('status' => 'open')),
                    'index'     => 300,
                    'class'     => 'fe fe-message-circle',
                ),
                array(
                    'location'  => 'support',
                    'label' => 'Canned responses',
                    'uri' => $this->di['url']->adminLink('support/canned-responses'),
                    'index'     => 400,
                    'class'     => 'fe fe-list',
                ),
            ),
        );
    }

    public function register(\Box_App &$app)
    {
        $app->get('/support',           'get_index', array(), get_class($this));
        $app->get('/support/',          'get_index', array(), get_class($this));
        $app->get('/support/index',     'get_index', array(), get_class($this));
        $app->get('/support/ticket/:id','get_ticket', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/support/ticket/:id/message/:messageid','get_ticket', array('id'=>'[0-9]+', 'messageid' => '[0-9]+'), get_class($this));
        $app->get('/support/public-tickets', 'get_public_tickets', array(), get_class($this));
        $app->get('/support/public-ticket/:id', 'get_public_ticket', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/support/helpdesks', 'get_helpdesks', array(), get_class($this));
        $app->get('/support/helpdesk/:id', 'get_helpdesk', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/support/canned-responses', 'get_canned_list', array(), get_class($this));
        $app->get('/support/canned/:id', 'get_canned', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/support/canned-category/:id', 'get_canned_cat', array('id'=>'[0-9]+'), get_class($this));
    }

    public function get_index(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_support_tickets');
    }
    public function support_staff_get(){
        return $this->di['db']->getAll("Select role,id,name,status FROM admin WHERE role='admin' OR role = 'staff' ORDER BY name");
    }
    public function support_dtto_get(){
        return $this->di['db']->getAll("Select name,id FROM support_helpdesk  ORDER BY name");
    }
    public function support_addons($id){
        $query="SELECT client.first_name,
client.last_name,
product.title,
client_order.title as details
            FROM
            support_ticket
            INNER JOIN client_order ON support_ticket.id_product_client = client_order.id
            INNER JOIN product ON client_order.product_id = product.id
            INNER JOIN client ON support_ticket.client_id = client.id 
            WHERE support_ticket.id = {$id}";
        $resp=$this->di['db']->getAll($query);
        return $resp[0];
    }
    /*public function get_staff_id($id){
        $query="SELECT client.first_name,
client.last_name,
product.title,
client_order.title as details
            FROM
            support_ticket
            INNER JOIN client_order ON support_ticket.id_product_client = client_order.id
            INNER JOIN product ON client_order.product_id = product.id
            INNER JOIN client ON support_ticket.client_id = client.id
            WHERE support_ticket.id = {$id}";
        $resp=$this->di['db']->getAll($query);
        return $resp[0];
    }*/

    public function support_logs_get($id){
        return $this->di['db']->getAll("Select * FROM tickets_logs WHERE id_ticket=".$id." ORDER BY created DESC");
    }
    public function get_ticket(\Box_App $app, $id, $messageid = '')
    {
        $api = $this->di['api_admin'];
        $ticket = $api->support_ticket_get(array('id'=>$id));
        $staffs = $this->support_staff_get();
        $dptos = $this->support_dtto_get();
        $logs = $this->support_logs_get($id);
        $addData = $this->support_addons($id);

        $cdm = '';
        $mod = $this->di['mod']('support');
        $config = $mod->getConfig();

        try {
            if(isset($config['delay_enable']) && $config['delay_enable'] && isset($config['delay_hours']) && $config['delay_hours'] >= 0) {
                $last_message = end($ticket['messages']);
                reset($ticket);

                $hours_passed = (round((time() - strtotime($last_message['created_at'])) / 3600) > $config['delay_hours']);
                if($hours_passed) {
                    $delay_canned = $api->support_canned_get(array('id'=>$config['delay_message_id']));
                    $cdm  = $delay_canned['content'];
                }
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
        //$arrayTemp=array();

        if(is_array($ticket['notes'])){
            $arrayTemp=array_merge($ticket['messages'],$ticket['notes']);

            array_multisort(array_column($arrayTemp, 'created_at'), SORT_ASC, $arrayTemp);
        }else{
            $arrayTemp=$ticket['messages'];
        }
        $ticket['finalList']=$arrayTemp;

        return $app->render('mod_support_ticket', array('ticket'=>$ticket, 'canned_delay_message'=>$cdm, 'request_message' => $messageid,'staffs'=>$staffs,'dptos'=>$dptos,'addons'=>$addData,'logs'=>$logs));
    }

    public function get_public_tickets(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_support_public_tickets');
    }

    public function get_public_ticket(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $ticket = $api->support_public_ticket_get(array('id'=>$id));
        return $app->render('mod_support_public_ticket', array('ticket'=>$ticket));
    }

    public function get_helpdesk(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $helpdesk = $api->support_helpdesk_get(array('id'=>$id));
        $staffs = $this->support_staff_get();
        $selStaff = $this->sel_staff($id);
        //print_r($selStaff); die;
        return $app->render('mod_support_helpdesk', array('helpdesk'=>$helpdesk,'staffs'=>$staffs,'sel_staff'=>$selStaff));
    }
    public function sel_staff($id){
        $resp=$this->di['db']->getAll("Select id_staff FROM support_helpdesk_staff WHERE id_support_helpdesk={$id}");
        $arr =array();
        foreach($resp as $rp){
            $arr[]=$rp['id_staff'];
        }
        return $arr;
    }
    public function get_helpdesks(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_support_helpdesks');
    }

    public function get_canned_list(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_support_canned_responses');
    }

    public function get_canned(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $c = $api->support_canned_get(array('id'=>$id));
        return $app->render('mod_support_canned_response', array('response'=>$c));
    }

    public function get_canned_cat(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $c = $api->support_canned_category_get(array('id'=>$id));
        return $app->render('mod_support_canned_category', array('category'=>$c));
    }

}

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

namespace Box\Mod\Staff\Controller;
use Box\InjectionAwareInterface;

class Admin implements InjectionAwareInterface
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
            'subpages'  =>  array(
                array(
                    'location'  => 'activity',
                    'index'     => 400,
                    'label' => 'Staff logins history',
                    'uri'   => $this->di['url']->adminLink('staff/logins'),
                    'class' => 'fe fe-pie-chart',
                ),
            ),
        );
    }

    public function register(\Box_App &$app)
    {
        $app->get('/staff/login',           'get_login', array(), get_class($this));
        $app->get('/staff/reset-password-confirm/:hash', 'get_reset_password_confirm', array('hash'=>'[a-z0-9]+'), get_class($this));
        $app->get('/staff/recover_password',           'get_recover_password', array(), get_class($this));
        $app->get('/staff/manage/:id',      'get_manage', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/staff/group/:id',      'get_group', array('id'=>'[0-9]+'), get_class($this));
        $app->get('/staff/profile',         'get_profile', array(), get_class($this));
        $app->get('/staff/logins',     'get_history', array(), get_class($this));
    }

    public function get_login(\Box_App $app)
    {
        // check if at least one admin exists.
        // if not show admin create form
        $service = $this->di['mod_service']('staff');
        $pass_recover_email = false;
        if($pass_recover_email = $this->di['session']->get('pass_recovered')){
            $this->di['session']->delete('pass_recovered');
        }
        $pass_staff_reset = false;
        if($pass_staff_reset = $this->di['session']->get('pass_staff_reset')){
            $this->di['session']->delete('pass_staff_reset');
            $pass_recover_email = false;
        }
        $count = $service->getAdminsCount();
        $create = ($count == 0);
        return $app->render('mod_staff_login', array(
                                                    'create_admin'=>$create, 
                                                    'pass_recover_email' => $pass_recover_email,
                                                    'pass_staff_reset' => $pass_staff_reset
                                                ));
    }
    public function get_reset_password_confirm(\Box_App $app, $hash)
    {
        //$api = $this->di['api_guest'];
        $data = array(
            'hash' =>  $hash,
        );
        $service = $this->di['mod_service']('staff');
        $service->staff_confirm_reset($data);
        $app->redirect('/staff/login');
    }
    public function get_recover_password(\Box_App $app)
    {
        // check if at least one admin exists.
        // if not show admin create form
        /*$service = $this->di['mod_service']('staff');
        $count = $service->getAdminsCount();
        $create = ($count == 0);*/
        return $app->render('mod_staff_recover_password');
    }

    public function get_profile(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        //$aunthenticator_2fa = $this->di['2faA_authenticator'];
        //$secret = $aunthenticator_2fa->generateRandomSecret();
        //$qrCodeUrl = $aunthenticator_2fa->getQR($mod['domain_username'].' ('.$users.')', $secret);
        //echo $qrCodeUrl; die;
        $api = $this->di['api_admin'];
        //die('ssss');
        return $app->render('mod_staff_profile', ['info_2fa' => $api->staff_member_2fainfo()]);
    }
    public function get_manage(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $staff = $api->staff_get(array('id'=>$id));

        $extensionService = $this->di['mod_service']("Extension");
        $mods = $extensionService->getCoreAndActiveModules();
        return $app->render('mod_staff_manage', array('staff'=>$staff, 'mods'=>$mods));
    }

    public function get_group(\Box_App $app, $id)
    {
        $api = $this->di['api_admin'];
        $group = $api->staff_group_get(array('id'=>$id));

        $extensionService = $this->di['mod_service']("Extension");
        $mods = $extensionService->getCoreAndActiveModules();
        return $app->render('mod_staff_group', array('group'=>$group, 'mods'=>$mods));
    }
    public function get_history(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_staff_login_history');
    }
}

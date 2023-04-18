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

namespace Box\Mod\Reminders\Controller;

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
                'index'     => 503,                // menu sort order
                'location'  =>  'reminders',          // menu group identificator for subitems
                'label'     => 'Reminders',    // menu group title
                'class'     => '',           // used for css styling menu item
            ),
            'subpages'=> array(
                array(
                    'location'  => 'reminders', // place this module in extensions group
                    'label'     => 'Settings',
                    'index'     => 112,
                    'uri'       => $this->di['url']->adminLink('reminders/setting'),
                    'class'     => 'fe fe-settings',
                ),
            ),
        );
    }
    public function register(\Box_App &$app)
    {
        $app->get('/reminders/setting', 'get_setting', array(), get_class($this));
    }
    public function get_setting(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        $servers=[];
        //$servers = $this->di['db']->find('ServiceHostingServer', 'ORDER BY id ASC');
        return $app->render('mod_reminders_setting',array('servers'=>$servers));
    }
    /*public function get_income(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_reports_income');
    }*/
}

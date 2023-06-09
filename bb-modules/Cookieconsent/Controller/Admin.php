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


namespace Box\Mod\Cookieconsent\Controller;

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
            'subpages'=> array(
                array(
                    'location'  => 'extensions',
                    'label'     => 'Cookie consent',
                    'index'     => 2000,
                    'uri'       => $this->di['url']->adminLink('cookieconsent'),
                    'class'     => 'fe fe-check',
                ),
            ),
        );
    }

    public function register(\Box_App &$app)
    {
        $app->get('/cookieconsent',           'get_index', array(), get_class($this));
    }

    public function get_index(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_cookieconsent_index');
    }
}

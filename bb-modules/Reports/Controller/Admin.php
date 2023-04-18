<?php
namespace Box\Mod\Reports\Controller;
class Admin extends \Api_Abstract
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
                'index'     => 502,                // menu sort order
                'location'  =>  'reports',          // menu group identificator for subitems
                'label'     => 'Reports',    // menu group title
                'class'     => '',           // used for css styling menu item
            ),
            'subpages'=> array(
                array(
                    'location'  => 'reports', // place this module in extensions group
                    'label'     => 'Accounts Reconciliation',
                    'index'     => 111,
                    'uri'       => $this->di['url']->adminLink('reports/reconciliation'),
                    'class'     => 'fe fe-server',
                ),
                array(
                    'location'  => 'reports', // place this module in extensions group
                    'label'     => 'Income Forecast',
                    'index'     => 112,
                    'uri'       => $this->di['url']->adminLink('reports/income'),
                    'class'     => 'fe fe-grid',
                ),
            ),
        );
    }
    public function register(\Box_App &$app)
    {
        $app->get('/reports/reconciliation', 'get_reconciliation', array(), get_class($this));
        $app->get('/reports/income', 'get_income', array(), get_class($this));
    }
    public function get_reconciliation(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        $servers = $this->di['db']->find('ServiceHostingServer', 'ORDER BY id ASC');
        return $app->render('mod_reports_reconciliation',array('servers'=>$servers));
    }
    public function get_income(\Box_App $app)
    {
        $this->di['is_admin_logged'];
        return $app->render('mod_reports_income');
    }
}
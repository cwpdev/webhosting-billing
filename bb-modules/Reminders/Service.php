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

namespace Box\Mod\Reminders;

class Service
{
    protected $di;

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
        CREATE TABLE IF NOT EXISTS `reminders` (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `modereminders` int(1) DEFAULT NULL,
            `daysbefore` varchar(255) DEFAULT NULL,
            `templateemail` varchar(255) DEFAULT NULL,
            `servicesuspend` int(1) DEFAULT NULL,
            `created_at` varchar(35) DEFAULT NULL,
            `updated_at` varchar(35) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->di['db']->exec($sql);

        $model                	= $this->di['db']->dispense('reminders');
        $model->id     	        = '';
        $model->modereminders   = 0;
        $model->daysbefore     	= '1,7,15,20,25';
        $model->templateemail   = '';
        $model->servicesuspend   = 0;
        $model->created_at    	= date('Y-m-d H:i:s');
        $model->updated_at    	= date('Y-m-d H:i:s');
        $id                     =   $this->di['db']->store($model);
        return $id;
    }
    public function uninstall()
    {
        $this->di['db']->exec("DROP TABLE IF EXISTS `reminders`");
        return true;
    }
    public function update($manifest)
    {
        //throw new \Box_Exception("Throw exception to terminate module update process with a message", array(), 125);
        return true;
    }
    public function getConfig()
    {
        $sql = 'SELECT * FROM reminders WHERE id = 1';
        $rows   = $this->di['db']->getAll($sql);
        $result  = $rows;
        return $result;
    }

    /*protected function _sendMail($admin, $admin_pass)
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
    }*/
}

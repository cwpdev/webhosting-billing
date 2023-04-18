<?php
namespace Box\Mod\Reports;

use Box\InjectionAwareInterface;

class Service implements InjectionAwareInterface
{
    /**
     * @var \Box_Di
     */
    protected $di = null;

    /**
     * @param \Box_Di $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return \Box_Di
     */
    public function getDi()
    {
        return $this->di;
    }

    public function getServerManager($model)
    {
        if(empty($model['manager'])) {
            throw new \Box_Exception('Invalid server manager. Server was not configured properly.', null, 654);
        }

        $config = array();
        $config['ip'] = $model['ip'];
        $config['host'] = $model['hostname'];
        $config['port'] = $model['port'];
        $config['secure'] = $model['secure'];
        $config['username'] = $model['username'];
        $config['password'] = $model['password'];
        $config['accesshash'] = $model['accesshash'];

        $manager = $this->di['server_manager']($model['manager'], $config);

        if(!$manager instanceof \Server_Manager) {
            throw new \Box_Exception('Server manager :adapter is not valid', array(':adapter'=>$model['manager']));
        }

        return $manager;
    }

    public function getaccountDetails($data)
    {

        $adapter = $this->getServerManager($data);
        $resp = $adapter->accountDetails();
        return $resp;
    }
}

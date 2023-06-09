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
 *Staff methods 
 */
namespace Box\Mod\Staff\Api;
class Guest extends \Api_Abstract
{
    /**
     * Gives ability to create administrator account if no admins exists on 
     * the system. 
     * Database structure must be installed before calling this action.
     * bb-config.php file must already be present and configured.
     * Used by automated BoxBilling installer.
     * 
     * @param string $email     - admin email
     * @param string $password  - admin password
     * 
     * @return bool
     */
    public function create($data)
    {

        $allow = (count($this->di['db']->findOne('Admin', '1=1')) == 0);
        if(!$allow) {
            throw new \Box_Exception('Administrator account already exists', null, 55);
        }
        $required = array(
            'email' => 'Administrator email is missing.',
            'password' => 'Administrator password is missing.',
        );
        $validator =  $this->di['validator'];
        $validator->checkRequiredParamsForArray($required, $data);
        $validator->isEmailValid($data['email']);
        $validator->isPasswordStrong($data['password']);

        $result = $this->getService()->createAdmin($data);
        if ($result){
            $this->login($data);
        }
        return true;
    }
    
    /**
     * Login to admin area and save information to session.
     * 
     * @param string $email     - admin email
     * @param string $password  - admin password
     * 
     * @optional string $remember  - pass value "1" to create remember me cookie
     * @return array
     * @throws Exception 
     */
    public function login($data)
    {
        $required = array(
            'email' => 'Email required',
            'password' => 'Password required',
        );
        $validator =  $this->di['validator'];
        $validator->checkRequiredParamsForArray($required, $data);

        $config = $this->getMod()->getConfig();

        //check ip
        if(isset($config['allowed_ips']) && isset($config['check_ip']) && $config['check_ip']) {
            $allowed_ips = explode(PHP_EOL, $config['allowed_ips']);
            if(!empty($allowed_ips)) {
                $allowed_ips = array_map('trim', $allowed_ips);
                if(!in_array($this->getIp(), $allowed_ips)) {
                    throw new \Box_Exception('You are not allowed to login to admin area from :ip address', array(':ip'=>$this->getIp()), 403);
                }
            }
        }

        return $this->getService()->login($data['email'], $data['password'], $this->getIp());
    }
    public function check_2fa($data){
        $required = array(
            'code_2fa' => 'Two-Factor Authentication Code Required',
        );
        //$validator =  $this->di['validator'];
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        $staff_pre_login = $this->di['session']->get('staff_pre_login');
        if(!is_array($staff_pre_login) || !array_key_exists('twofactor_secret', $staff_pre_login)) {
            throw new \Box_Exception('Invalid 2fa information', null, 403);
        }
        if($this->di['2faA_authenticator']->verifyCode($staff_pre_login['twofactor_secret'], $data['code_2fa'])){
            $this->di['events_manager']->fire(['event'=>'onAfterAdminLogin', 'params' => ['id' => $staff_pre_login['id'], 'ip' => $this->getIp()]]);
            unset($staff_pre_login['twofactor_secret']);
            $this->di['session']->delete('staff_pre_login');
            $this->di['session']->set('admin', $staff_pre_login);
            return true;
        }
        throw new \Box_Exception('Invalid Two-Factor Authentication Code', null, 403);
    }
    /**
     * Recover password for staff.
     * 
     * @param string $email     - admin email
     * 
     * @return array
     * @throws Exception 
     */
    public function recover_password($data){
        $required = array(
            'email'         => 'Email required',
        );
        $this->di['validator']->checkRequiredParamsForArray($required, $data);
        //$model = $this->di['db']->findOne('Admin', 'email = ? AND status = ?', array($data['email'], \Model_Admin::STATUS_ACTIVE));
        return $this->getService()->recover_password($data['email'], $this->getIp());
    }
    
}
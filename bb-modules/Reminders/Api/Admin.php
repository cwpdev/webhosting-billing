<?php
namespace Box\Mod\Reminders\Api;
class Admin extends \Api_Abstract
{
    public function get_accountServer($data){
        if(isset($data['server']) && !empty($data['server']) && is_numeric($data['server'])){
            $rep =  $this->di['db']->findOne('service_hosting_server', 'id = :id', array('id'=>$data['server']));
            $ip = trim($rep['ip']);
            $accouts = $this->di['db']->getAll("SELECT * FROM service_hosting WHERE ip = '{$ip}'");

            $api = $this->accountDetails($rep);
            $svaccounts=[];
            foreach($api as $accServer){
                $svaccounts[$accServer->username]['username']=$accServer->username;
                $svaccounts[$accServer->username]['reseller']=$accServer->reseller;
                $svaccounts[$accServer->username]['domain']=$accServer->domain;
                $svaccounts[$accServer->username]['status']=$accServer->status;
            }


            $table = '';
            foreach($accouts as $account){
                $username = (empty($account['username']))?'s/n':$account['username'];
                $reseller = ($account['reseller']==0)?'NO':$account['reseller'];
                $status = $this->searchStatus($account['sld'].'.'.$account['tld'],$account['service_hosting_hp_id']);
                $color = $this->colorBg($username,$svaccounts);
                $table .= '<tr style="background-color:'.$color.'">
                            <td>'.$username.'</td>
                            <td>'.$account['sld'].'.'.$account['tld'].'</td>
                            <td>'.$reseller.'</td>
                            <td>'.$this->packageName($account['service_hosting_hp_id']).'</td>
                            <td><a href="../../bb-admin/client/manage/'.$account['client_id'].'"><span class="badge  bg-info-transparent text-dark ">'.$this->clientName($account['client_id']).'</span></a></td>
                            <td>'.$status.'</td>
                            <td>
                            <a class="btn btn-sm  bg-info-transparent text-info" href=""><i class="fe fe-edit"></i></a>
                            <a class="btn btn-sm  bg-danger-transparent text-danger bb-rm-tr api-link" href="" data-api-confirm="Are you sure?" data-api-reload="1"><i class="fe fe-trash"></i></a>
                            </td>
                            </tr>';
            }
            $return = ['status'=>'OK','html'=>$table];
        }else{
            $return =['status'=>'Error','msj'=>'You must indicate a valid parameter'];
        }
        return json_encode($return);
    }
    public function getIdata($data){
        $rep =  $this->di['db']->findOne('reminders', 'id = :id', array('id'=>1));

        $tpls = $this->di['db']->getAll("SELECT * FROM email_template WHERE enabled = 1 ORDER BY category");

        $return = ['status'=>'OK','result'=>$rep,'tpls'=>$tpls];
        return json_encode($return);
    }
    public function savedata($data){
        $model                	= $this->di['db']->dispense('reminders');
        $model->id     	        = 1;
        $model->modereminders   = (!isset($data['modereminders']) || $data['modereminders']!='on')?0:1;
        $model->daysbefore     	= ($data['daysbefore']=='')?'0,':trim($data['daysbefore']);
        $model->templateemail   = trim($data['templateemail']);
        $model->servicesuspend   = (!isset($data['servicesuspend']) || $data['servicesuspend']!='on')?0:1;
        $model->updated_at    	= date('Y-m-d H:i:s');
        $id                     =   $this->di['db']->store($model);
        $return = ['status'=>'OK'];
        return json_encode($return);
    }
    public function gettpl($data){
        $rep =  $this->di['db']->findOne('email_template', 'id = :id', array('id'=>$data['id']));
        return json_encode($rep);
    }
    public function batch_send_reminders($data)
    {
        shell_exec("echo '".json_encode($data)."' >> /home/araguave/testcron.log");
        return true;
    }
}

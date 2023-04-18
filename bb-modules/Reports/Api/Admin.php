<?php
namespace Box\Mod\Reports\Api;
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
    public function packageName($id){
        $rep =  $this->di['db']->findOne('service_hosting_hp', 'id = :id', array('id'=>$id));
        return $rep['name'];
    }
    public function clientName($id){
        $rep =  $this->di['db']->findOne('client', 'id = :id', array('id'=>$id));
        return "{$rep['first_name']} {$rep['last_name']}";
    }
    public function accountDetails($data)
    {
        return $this->getService()->getaccountDetails($data);
    }
    public function getIncomeReport($data){
        $date_now = date('Y-m-').'01';
        $date_future = strtotime('+11 month', strtotime($date_now));
        $date_future = date('Y-m-t', $date_future);
        $query = "SELECT 
                    SUM(price) as price, 
                    month(expires_at) as mes, 
                    YEAR(expires_at) as anio, 
                    period 
                FROM client_order
                WHERE status = 'Active' AND expires_at > '{$date_now} 00:00:01' AND expires_at < '{$date_future} 23:59:59' 
                GROUP BY YEAR(expires_at), month(expires_at), period";
        $charges = $this->di['db']->getAll($query);
        $numMes=0;
        $table ='';
        $matriz = $charges;
        foreach($charges as $resul){
            if($numMes!=$resul['mes']) {
                $table .= $this->searchResult($matriz,$resul['mes'],$resul['anio']);
                $numMes=$resul['mes'];
            }
        }

        $return = ['status'=>'OK','table'=>$table,'query'=>$query];
        return json_encode($return);
    }
    public function monthName($monthNum){
        $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
        return $monthName;
    }
    public function colorBg($account,$listAccount){
        if (isset($listAccount[$account])) {
            return '#ffffff';
        }else{
            return '#f1f1f1';
        }
    }
    public function searchStatus($domain,$id_product){
        $return = $this->di['db']->getAll("SELECT status FROM client_order WHERE title LIKE '%{$domain}%' AND product_id = {$id_product} AND service_type = 'hosting' ORDER BY id DESC LIMIT 0,1");
        if(trim($return[0]['status'])=='canceled' || trim($return[0]['status'])=='Terminated'){
            $st='<span class="badge bg-danger me-1">'.$return[0]['status'].'</span>';
        }else if(trim($return[0]['status'])=='active'){
            $st='<span class="badge bg-primary me-1 my-2">'.$return[0]['status'].'</span>';
        }else if(trim($return[0]['status'])=='suspended' || trim($return[0]['status'])=='Suspended'){
            $st='<span class="badge bg-warning me-1 my-2">'.$return[0]['status'].'</span>';
        }else if(trim($return[0]['status'])=='pending_setup'){
            $st='<span class="badge bg-info me-1 my-2">'.$return[0]['status'].'</span>';
        }else{
            $st=$return[0]['status'];
        }
        return $st;
    }
    public function searchResult($rows,$mes,$anio){
        $w1 = 0; $m1 = 0; $m3 = 0; $m6 = 0; $y1 = 0; $y2 = 0; $y3 = 0;
        $monthName = $this->monthName($mes);

        foreach($rows as $row){
            if($row['mes']==$mes AND $row['anio']==$anio){
                if($row['period']=='1W'){$w1=$row['price'];}
                if($row['period']=='1M'){$m1=$row['price'];}
                if($row['period']=='3M'){$m3=$row['price'];}
                if($row['period']=='6M'){$m6=$row['price'];}
                if($row['period']=='1Y'){$y1=$row['price'];}
                if($row['period']=='2Y'){$y2=$row['price'];}
                if($row['period']=='3Y'){$y3=$row['price'];}
            }
        }
        $total = $w1 + $m1 + $m3 + $m6 + $y1 + $y2 + $y3;
        $html="<tr>
                    <td>{$monthName} {$anio}</td>
                    <td class=\"text-right\">{$w1}</td>
                    <td class=\"text-right\">{$m1}</td>
                    <td class=\"text-right\">{$m3}</td>
                    <td class=\"text-right\">{$m6}</td>
                    <td class=\"text-right\">{$y1}</td>
                    <td class=\"text-right\">{$y2}</td>
                    <td class=\"text-right\">{$y3}</td>
                    <td class=\"text-right\">{$total}</td>
                </tr>";
    }
}

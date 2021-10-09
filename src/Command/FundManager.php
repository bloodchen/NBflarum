<?php

namespace Chen\NbdomainLogin\Command;

use Flarum\User\User;
use Chen\NbdomainLogin\Nbdomaintips;

function mlog(){
        $args = func_get_args();
        foreach ($args as $arg){
            file_put_contents('debug.txt', var_export($arg,true)."\n",FILE_APPEND);
        }
}

function getRandomMoney( &$redPackage) {
     // remainSize 剩余的红包数量
     // remainMoney 剩余的钱
     if($redPackage["remainSize"] == 1) {
         $redPackage["remainSize"]--;
         return intval($redPackage["remainMoney"]);
     }
     $max   = $redPackage["remainMoney"] / $redPackage["remainSize"] * 2 * 100;
     $money = random_int(1,$max)/100;
     $redPackage["remainSize"]--;
     $redPackage["remainMoney"] -= $money;
     return intval($money);
 }

function do_post($url, $params) {
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => $params
        )
    );
    $result = file_get_contents($url, false, stream_context_create($options));
    return $result;
}

$paying = false;
class FundManager
{
    
    /**
     * @param User  $actor The user performing the action.
     * @param array $data  The attributes of the new reaction.
     */
    public static function fundDraw()
    {
        //if(date('d')!="01"||date('d')!="02") return; //only pay on 1st and 2nd day of the month

        if($paying) return;
        $finishedFile = date('Y-m',strtotime("last month")).".json";
        if(file_exists("../packages/records/".$finishedFile)) return; //already paid

        $from = date("Y-m-d", strtotime("first day of previous month"));
        $to =  date("Y-m-d", strtotime("last day of previous month"));
        $records = FundManager::tipRecordsFromTime($from,$to);
        $count = count($records);
        if($count==0) return; //no one to distribute

        $paying = true;
        mkdir("../packages/records/");
        $ret = json_decode(file_get_contents("https://tnode.nbdomain.com/api/address/12vxiP1nhNVDXwegdzV3P5EnS9MVRHW45Z/balance"));
        $balance = $ret->confirmed+$ret->unconfirmed;

        $redPacket = array("remainSize"=>$count,"remainMoney"=>intval($balance*0.8)); //80% fund distributed to tipped users

        $result = array("txid"=>"","users"=>array());
        $to = array("to"=>array());
        foreach($records as $item){
            $sender = $item->getAttribute('sender_id');
            $user = User::find($sender);
            $money = getRandomMoney($redPacket);
            $result["users"][$user->opayaddress] = $money;
            array_push($to["to"],array("address"=>$user->opayaddress,"value"=>$money));
        }
        mlog(json_encode($to));
        //todo call payment
        $string = file_get_contents("../packages/nbflarum.json");
        $json_a = json_decode($string);
        $to["key"]=base64_encode($json_a->fund->privateKey);
        $payment_res = do_post('https://mpoint-api.glitch.me/v1/util/datapay', json_encode($to));
        mlog($payment_res);
        $result["ret"] = $payment_res;

        $result["txid"] = json_decode($payment_res)->txid;
        //
        $fp = fopen("../packages/records/".$finishedFile, 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);
        $paying = false;
    }
    public static function tipRecordsFromTime($from, $to)
    {
        $tips = Nbdomaintips::whereBetween('date', [$from, $to])->get();
        return $tips;
    }
    public static function getAddress()
    {
        $string = file_get_contents("../packages/nbflarum.json");
        $json_a = json_decode($string);
        
        return $json_a->fund->address;
    }
}

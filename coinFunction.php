<?php
    require_once 'global.php';
    use RedBeanPHP\R;

    function validateFields($chkfields,$p){
        $response = true;

        if(empty($p))
            $response = false;
        
        foreach($chkfields as $field)
            if(!isset($p[$field]))
                $response = false;

        return $response;
    }

    function regUser($p){
        $response = true;
        $chkfields = array("email","name","fbtoken");   
        $response = validateFields($chkfields,$p);

        if($response){
            $reg = R::dispense('user');
            $reg->email = $p["email"];
            $reg->name = $p["name"];
            $reg->fbtoken = $p["fbtoken"];
            $reg->create_time = date('Y-m-d H:i:s');
            R::store($reg);
        }
        return $response;
    }

    function signIn($p){
        $response = true;
        $chkfields = array("email","fbtoken");   
        $response = validateFields($chkfields,$p);

        if($response){
            $login = R::find( 'user', ' email = ? ', [ $p["email"] ]);
            if(empty($login))
                $response = false;
        }
        return $response;
    }

    function generateToken($p){
        $response = com_create_guid();
        $login = R::find( 'user', ' email = ? ', [ $p["email"] ]);

        $user = R::load( 'user' , $login->id);
        $user->token = $response;
        R::store($user);

        return $response;
    }

    function logLocation($p){
        $response = true;
        $chkfields = array("token","lat","lng","log_time");        
        $response = validateFields($chkfields,$p);
        
        if($response){
            $log = R::dispense('location');
            $log->user_id = getUserIdViaToken($p["token"]);
            $log->lat = $p["lat"];
            $log->lng = $p["lng"];
            $log->log_time = $p["log_time"];
            $log->create_time = date('Y-m-d H:i:s');
            R::store($log);
        }
        return $response;
    }

    function getUserIdViaToken($token){
        $response = "";
        $login = R::find( 'user', ' token = ? ', [ $token ]);
        $response = $login->id;
        return $response;
    }

    function isValidToken($token){
        $response = false;
        $login = R::find( 'user', ' token = ? ', [ $token ]);
        if(!empty($login))
            $response = true;
        return $response;
    }
    
    function listRate($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        return R::findAll('exchangerate', $filter . ' ORDER BY dest_cur ASC, create_time DESC',$filter_value);
    }

    function listOffer($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        return R::findAll('offer', $filter . ' ORDER BY create_time DESC',$filter_value);
    }

    function listTransaction($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        return R::findAll('transaction', $filter . ' ORDER BY create_time DESC',$filter_value);
    }

    function crtTransaction($p){
        $transaction = R::dispense('transaction');
        $transaction->date = date('Y-m-d');
        $transaction->src_cur = $p["src_cur"];
        $transaction->src_amt = $p["src_amt"];

        $transaction->offer_rate = $p["offer_rate"];

        $transaction->dest_cur = $p["dest_cur"];
        $transaction->dest_amt = $p["dst_amt"];

        $transaction->src_user = $p["src_user"];
        $transaction->dest_user = $p["dest_user"];
        $transaction->refer_id = $p["refer_id"]; 
        $transaction->status = "000";
        $transaction->create_time = date('Y-m-d H:i:s');
        R::store($transaction);
    }

    function updTransction($p){
         $transaction = R::load( 'transaction' , $p["id"]);
         $transaction->status = $p["status"];
         R::store($transaction);
    }

    function crtOffer($p){
        $offer = R::dispense('offer');
        $offer->date = date('Y-m-d');
        $offer->offer_cur = $p["offer_cur"];
        $offer->offer_amt = $p["offer_amt"];
        $offer->status = "000";
        $offer->create_time = date('Y-m-d H:i:s');
        R::store($offer);
    }

?>
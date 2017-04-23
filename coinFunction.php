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
        $login = R::find( 'user', ' token = ? ', array( $token ));
        $response = $login->id;
        return $response;
    }

    function isValidToken($token){
        $response = false;
        $login = R::find( 'user', ' token = ? ', array( $token ));
        if(!empty($login))
            $response = true;
        return $response;
    }
    
    function generateFilter($acptfilter,$p){
        $acptfilter = array("date","src_cur","dest_cur");
        $rtn = array();
        $filter = "";
        $filter_field = array();
        $filter_value = array();
        foreach($acptfilter as $k=>$d)
            if(isset($p[$k])&&!empty($p[$k])){
                $filter_field[] = $k."= ? ";
                $filter_value[] = $d;
            }
        
        if(!empty($filter_value))
            $filter = implode(" AND ",$filter_field);

        $rtn["f"] = $filter;
        $rtn["v"] = $filter_value;

        return $rtn;
    }

    function listRate($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        $acptfilter = array("date","src_cur","dest_cur");
        $frst = generateFilter($acptfilter,$p);
        $filter = $frst["f"];
        $filter_value = $frst["v"];        
        
        return R::find('exchangerate', $filter . ' ORDER BY dest_cur ASC, create_time DESC',$filter_value);
    }

    function listOffer($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        $acptfilter = array("offer_cur","status");
        $frst = generateFilter($acptfilter,$p);
        $filter = $frst["f"];
        $filter_value = $frst["v"];        

        return R::find('offer', $filter . ' ORDER BY create_time DESC',$filter_value);
    }

    function listTransaction($p){
        //$filter - the condition (string)
        //$filter_value - the condition value (array)
        $acptfilter = array("date","src_cur","dest_cur","status");
        $frst = generateFilter($acptfilter,$p);
        $filter = $frst["f"];
        $filter_value = $frst["v"];        

        return R::find('transaction', $filter . ' ORDER BY create_time DESC',$filter_value);
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
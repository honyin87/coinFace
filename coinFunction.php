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
    
?>
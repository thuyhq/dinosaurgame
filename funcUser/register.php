<?php
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
		$configs = include('./../config.php');
		$public_link = $configs['public_link'];
		$url_mongodb = $configs['url_mongodb'];
		$databaseName = $configs['databaseName'];
		
        $oldUser = array();
        $pwd = urldecode($_REQUEST["pwd"]);
        $email = strip_tags(urldecode($_REQUEST["email"]));


        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongodb);
        //update sorce user
        $filter = ['email' => $email ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);          
        $res = $mng->executeQuery("dino2.user", $query);
        $cursor = current($res->toArray());
        $userInfo = array(
            'userName'=> explode('@',$email)[0],
            'email'=> $email,
            'pwd'=> md5($pwd)
        ); 
        if($cursor){
            //return messsage exist email
            //không có
            $msg = array('msg' => "exist email");
            $jsonstring = json_encode($msg);
            echo $jsonstring;
        }else{
            //không có inssert db return success
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($userInfo);
            //excute mongodb command
            $mng->executeBulkWrite('dino2.user', $bulk);

            //save cookies
            $cookie_name = "userInfo_name";
            $cookie_value =  $userInfo['userName'];
            
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day

            //không có
            $msg = array('msg' => "register success");
            $jsonstring = json_encode($msg);
            echo $jsonstring;
        }

        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }

?>
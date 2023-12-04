<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $configs = include('./../config.php');
    $public_link = $configs['public_link'];
    $date_of_expiry = time() - 60 ;

    $request_body = file_get_contents('php://input');
    $request_data = json_decode($request_body);

    //image url.
    $userInfo = array(
        'email'=> $request_data->email,
        'fb_id'=> $request_data->id,
        'fb_userName' => $request_data->last_name .' '. $request_data->first_name,
        'first_name' =>$request_data->first_name,
        'last_name' => $request_data->last_name
    );
    
    try {
        //Get the file
        $image_link = $request_data->picture;
        //note check tree folder
        $linkFolderImage = "../user/fb/".$request_data->id."/avatar/";

        if (!file_exists($linkFolderImage)) {
            mkdir($linkFolderImage, 0777, true);
        }
        $avatarName = "fb_avatar".".jpg";
        $file_name = $linkFolderImage.$avatarName;

        $content = file_get_contents($image_link);
        file_put_contents($file_name, $content);
        $userInfo["fb_avatar"] = $avatarName;
        $userInfo["avatar"] = str_replace('../','/',$file_name);
		

    } catch(\Exceptions $e) {
        echo 'Error: ' . $e->getMessage();
        exit;
    } 

    //get content success then save to mongoDB
    //conect mongoDB
    try{
        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager("mongodb://dinosaurgame.io");
        $filter = ['email' => $userInfo["email"] ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery("dino2.user", $query);
        $cursor = current($res->toArray());
        if($cursor){
            //update
            if($cursor -> fb_id == NULL){
                $bulk->update(
                            ['email' => $userInfo["email"]], 
                            ['$set' =>  [
                                            'fb_id' => $userInfo["fb_id"],
                                            'fb_avatar' =>  $userInfo,
                                            'fb_userName' => $userInfo["last_name"] .' '. $userInfo["first_name"]
                                        ]
                            ]
                        );
                //excute mongodb command
                $mng->executeBulkWrite('dino2.user', $bulk);
            }
            if(isset($cursor->userName)){
                $usName = $cursor->userName;
            }else{
                $usName = $userInfo["last_name"] .' '. $userInfo["first_name"];
            }
            
            $cookie_name = "userInfo_name";
            if($usName != NULL){
                $cookie_value =  $usName;
            }else{
                $cookie_value =  $userInfo['fb_userName'];
            }

            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo["avatar"];

            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
            
            if(isset($cursor -> hightScore)){              
                //set highScore
                $cookie_highScore = "usScore";
                $cookie_value_score =  $cursor -> hightScore;
                setcookie($cookie_highScore, $cookie_value_score, time()+24*60*60, "/"); // 86400 = 1 day               
            }
          

            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
            
            //echo "<script>location.hash = 'index'</script>";
        }else{
            //insert new
            //$userInfo -> _id = new MongoDB\BSON\ObjectID;
            $bulk->insert($userInfo);
            //excute mongodb command
            $mng->executeBulkWrite('dino2.user', $bulk);

            $cookie_name = "userInfo_name";
            $cookie_value =  $userInfo['fb_userName'];
        
            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo["avatar"];
               
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day

            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
            
            //echo "<script>location.hash = 'index'</script>";
        }

        $msg = array(
                'msg' => "login success",
                'success' => true
            );
        $jsonstring = json_encode($msg);
        echo $jsonstring;

    }catch(\Exceptions $e){
        echo "<pre>"; print_r($e); echo "</pre>";
    }

    exit();
?>


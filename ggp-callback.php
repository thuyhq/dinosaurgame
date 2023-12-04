<?php
    require_once __DIR__ . '/google-api-php-client/vendor/autoload.php';

    $configs = include('./config.php');
    $public_link = $configs['public_link'];
    $url_mongodb = $configs['url_mongodb'];
    $databaseName = $configs['databaseName'];
    $client = new Google_Client();
    $client->setAuthConfigFile('google-api-php-client/vendor/client_credentials.json');
    
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if(isset($token['error_description'])){
        header('Location: '.$public_link);
    }
    $access_token = $token['access_token'];
    $date_of_expiry = time() - 60 ;
    
    $q = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token='.$access_token;
    $json = file_get_contents($q);
    $user_info_gp = json_decode($json,true);

    //$googleEmail = $userInfoArray['email'];
    //$googleFirstName = $userInfoArray['given_name'];
    //$googleLastName = $userInfoArray['family_name'];

    try
    {
        //Get the file
        $image_link = $user_info_gp['picture'];
        $split_image = pathinfo(parse_url($image_link)['path']);

        $linkFolderImage = "./user/gp/".$user_info_gp['id']."/avatar/";

        if (!file_exists($linkFolderImage)) {
            mkdir($linkFolderImage, 0777, true);
        }
        $avatarName = "gp_avatar".".".$split_image['extension'];
        $file_name = $linkFolderImage.$avatarName;
        

        $content = file_get_contents($image_link);
        file_put_contents($file_name, $content);
    }catch(\Exception $e) {
        echo 'save file error: ' . $e->getMessage();
        exit;
    }

    //get content success then save to mongoDB
    //conect mongoDB

    try{
        $userInfo = array(
            'email'=> $user_info_gp['email'],
            'gp_id'=> $user_info_gp['id'],
            'gp_avatar'=> $avatarName,
            'gp_userName' => $user_info_gp['family_name'] ." " . $user_info_gp['given_name'],
            'avatar' => $file_name
        );

        //var_dump(json_decode(json_encode($userInfo)));
        //var_dump($user_info_gp->email);
        //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('cso');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('email' => $user_info_gp['email']));
        // if($cursor->count() == 0){
        //     //insert new
        //     $collection->insert($userInfo);
        //     // //Save to session
        //     $cookie_name = "userInfo_name";
        //     $cookie_value = $userInfo["gp_userName"];
        
        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
               
        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
        // }else{
        //     //update info
        //     foreach($cursor as $doc){
        //         $oldUser = $doc;
        //         break;
        //     }

        //     //update facebook info
        //     if(!array_key_exists("gp_id",$oldUser) && $oldUser["gp_id"] == NULL){
        //         $oldUser["gp_id"] = $user_info_gp['id'];
        //         $oldUser["gp_avatar"] = $avatarName;
        //         $oldUser["gp_userName"] = $user_info_gp['gp_userName'];
        //     }
        //     $collection->update(array("_id" => new MongoId($oldUser["_id"])),$oldUser);
        //     // //Save to session
        //     $cookie_name = "userInfo_name";
        //     if(isset($oldUser["userName"])){
        //         //changed userName
        //         $cookie_value =  $oldUser["userName"];
        //     }else{
        //         $cookie_value =  $oldUser["gp_userName"];
        //     }
        
        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
               
        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongodb);
        $filter = ['email' => $user_info_gp['email'] ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery($databaseName."user", $query);
        $cursor = current($res->toArray());
        if($cursor){
            //update
            if(!isset($cursor -> gp_id)){
                $bulk->update(
                            ['email' => $user_info_gp['email']], 
                            ['$set' =>  [
                                            'gp_id' => $user_info_gp['id'],
                                            'gp_avatar' =>  $avatarName,
                                            'gp_userName' => $user_info_gp['family_name'] ." " . $user_info_gp['given_name']
                                        ]
                            ]
                        );
                //excute mongodb command
                $mng->executeBulkWrite($databaseName.'user', $bulk);
            }
            $usName = $cursor ->userName;
            //$_SESSION["userInfo_name"] =  $cursor ->userName;
            $cookie_name = "userInfo_name";

            if($usName != NULL){
                $cookie_value =  $usName;
            }else{
                $cookie_value =  $userInfo['gp_userName'];
            }
        
            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
            
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day

            if($cursor -> hightScore != null){
                //set highScore
                $cookie_highScore = "usScore";
                $cookie_value_score =  $cursor -> hightScore;
                setcookie($cookie_highScore, $cookie_value_score, time()+24*60*60, "/"); // 86400 = 1 day
            }
            
            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
        }else{
            //insert new
            $userInfo -> _id = new MongoDB\BSON\ObjectID;
            $bulk->insert($userInfo);
            //excute mongodb command
            $mng->executeBulkWrite($databaseName.'user', $bulk);
            // //Save to session
            $cookie_name = "userInfo_name";
            $cookie_value =  $userInfo['gp_userName'];
        
            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
            
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day

            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
        }
        
        // header('Location: '.$public_link);
        echo "<script>window.close();</script>";
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }


?>
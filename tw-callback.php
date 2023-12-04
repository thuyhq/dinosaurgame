<?php
    require_once  __DIR__ . '/twitteroauth/autoload.php';
    use Abraham\TwitterOAuth\TwitterOAuth;
    session_start();

    $configs = include('./config.php');
    $public_link = $configs['public_link'];
    $url_mongodb = $configs['url_mongodb'];
    $databaseName = $configs['databaseName'];

    $oauth_verifier = filter_input(INPUT_GET, 'oauth_verifier');
    
    if (empty($oauth_verifier) ||
        empty($_COOKIE['oauth_token']) ||
        empty($_COOKIE['oauth_token_secret'])
    ) {
        // something's missing, go and login again
        header('Location: ' . $configs['url_login']);
    }


    // connect with application token
    $connection = new TwitterOAuth(
        $configs['consumer_key'],
        $configs['consumer_secret'],
        $_COOKIE['oauth_token'],
        $_COOKIE['oauth_token_secret']
    );
    
    // request user token
    $token = $connection->oauth(
        'oauth/access_token', [
            'oauth_verifier' => $oauth_verifier
        ]
    );

    $twitter = new TwitterOAuth(
        $configs['consumer_key'],
        $configs['consumer_secret'],
        $token['oauth_token'],
        $token['oauth_token_secret']
    );
    $date_of_expiry = time() - 60 ;
    $user_info_tw = $twitter->get("account/verify_credentials", ['include_entities' => 'false','include_email'=>'true','skip_status'=>'true',]);

    // var_dump($user_info_tw);
    // die;
    // var_dump($user_info_tw->id_str);
    // var_dump($user_info_tw->email);
    // var_dump($user_info_tw->screen_name);
    // var_dump($user_info_tw->profile_image_url); //repalace _normal = ""

    try
    {
        //Get the file
        $image_link = $user_info_tw->profile_image_url;
        $split_image = pathinfo(parse_url($image_link)['path']);

        $linkFolderImage = "./user/tw/".$user_info_tw->id_str."/avatar/";

        if (!file_exists($linkFolderImage)) {
            mkdir($linkFolderImage, 0777, true);
        }
        $avatarName = "tw_avatar".".".$split_image['extension'];
        $file_name = $linkFolderImage.$avatarName;
        

        $content = file_get_contents($image_link);
        file_put_contents($file_name, $content);
    }catch(Exception $e) {
        echo 'save file error: ' . $e->getMessage();
        exit;
    }
    

    //get content success then save to mongoDB
    //conect mongoDB
    try{
        $userInfo = array(
            'email'=> $user_info_tw->email,
            'tw_id'=> $user_info_tw->id_str,
            'tw_avatar'=> $avatarName,
            'tw_userName' => $user_info_tw->screen_name,
            'avatar' => $file_name
        );
        //var_dump(json_decode(json_encode($userInfo)));
        //var_dump($user_info_tw->email);
        // //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('cso');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('email' => $user_info_tw->email));
        // if($cursor->count() == 0){
        //     //insert new
        //     $collection->insert($userInfo);
        //     // //Save to session
        //     $cookie_name = "userInfo_name";
        //     $cookie_value = $userInfo["tw_userName"];

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
        //     if(!array_key_exists("tw_id",$oldUser) && $oldUser["tw_id"] == NULL){
        //         $oldUser["tw_id"] = $user_info_tw->id_str;
        //         $oldUser["tw_avatar"] = $avatarName;
        //         $oldUser["tw_userName"] = $user_info_tw->screen_name;
        //     }
        //     $collection->update(array("_id" => new MongoId($oldUser["_id"])),$oldUser);
        //     // //Save to session
        //     $cookie_name = "userInfo_name";
        //     if(isset($oldUser["userName"])){
        //         //changed userName
        //         $cookie_value =  $oldUser["userName"];
        //     }else{
        //         $cookie_value =  $oldUser["fb_userName"];
        //     }
        
        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
               
        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongodb);
        $filter = ['email' => $user_info_tw->email ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery($databaseName."user", $query);
        $cursor = current($res->toArray());
        if($cursor){
            //update
            if($cursor -> tw_id == NULL){
                $bulk->update(
                            ['_id' => new \MongoDB\BSON\ObjectId($cursor -> _id)], 
                            ['$set' =>  [
                                            'tw_id' => $user_info_tw->id_str,
                                            'tw_avatar' =>  $avatarName,
                                            'tw_userName' => $user_info_tw->screen_name
                                        ]
                            ]
                        );
                //excute mongodb command
                $mng->executeBulkWrite($databaseName.'user', $bulk);
            }
            $cookie_name = "userInfo_name";
            if($usName != NULL){
                $cookie_value =  $usName;
            }else{
                $cookie_value =  $userInfo['tw_userName'];
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
            $cookie_value =  $userInfo['tw_userName'];
            
            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
            
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
        }
        
        //header('Location: '.$public_link);
        // $msg = array(
        //     'msg' => "login success",
        //     'userInfo' => $userInfo
        // );
        // $jsonstring = json_encode($msg);
        // echo $jsonstring;
        echo "<script>window.close();</script>";
    }catch(\Exceptions $e){
        echo "<pre>"; print_r($e); echo "</pre>";
    }


?>
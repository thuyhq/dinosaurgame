
<?php
    //get content success then save to mongoDB
    //conect mongoDB
    $configs = include('../config.php');
    $public_link = $configs['public_link'];
    $url_mongodb = $configs['url_mongodb'];
    $databaseName = $configs['databaseName'];
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    try{
        $oldUser = array();
        $pwd = urldecode($_REQUEST["pwd"]);
        $email = urldecode($_REQUEST["email"]);

        // //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('cso');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('email' => $email, 'pwd' => md5($pwd)));
        // if($cursor->count() == 0){
        //     //không có
        //     $msg = array('msg' => "login fails");
        //     $jsonstring = json_encode($msg);
        //     echo $jsonstring;
        // }else{
        //     //update info
        //     foreach($cursor as $doc){
        //         $oldUser = $doc;
        //         break;
        //     }

        //     $cookie_name = "userInfo_name";
            
        //     if(isset($oldUser['userName'])){
        //         $cookie_value =  $oldUser['userName'];  
        //     }else if(isset($oldUser['fb_userName'])){    
        //         $cookie_value =  $oldUser['fb_userName'];   
        //     }else if(isset($oldUser['tw_userName'])){
        //         $cookie_value =  $oldUser['tw_userName'];    
        //     }else if(isset($oldUser['gp_userName'])){
        //         $cookie_value =  $oldUser['gp_userName'];  
        //     }

        //     $linkFolderImage = "user/noImg.jpg";
        //     if(isset($oldUser['fb_avatar'])){     
        //         $linkFolderImage = "user/fb/".$oldUser['fb_id']."/avatar/".$oldUser['fb_avatar']; 
        //     }else if(isset($oldUser['tw_avatar'])){
        //         $linkFolderImage = "user/tw/".$oldUser['tw_id']."/avatar/".$oldUser['tw_avatar'];
        //     }else if(isset($oldUser['gp_avatar'])){
        //         $linkFolderImage = "user/gp/".$oldUser['gb_id']."/avatar/".$oldUser['gp_avatar'];  
        //     }

        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $linkFolderImage;
               
        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day

        //     $jsonstring = json_encode($oldUser);
        //     echo $jsonstring;
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongodb);
        $filter = [
                '$and' => [
                    ['email'=> $email],
                    ['pwd' => md5($pwd)]
                ]
            ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery( $databaseName."user", $query);
        $cursor = current($res->toArray());

        if($cursor && $cursor->email != NULL){
            $cookie_name = "userInfo_name";
            if(isset($cursor->userName)){
                $cookie_value =  $cursor->userName;  
            }else if(isset($oldUser['fb_userName'])){    
                $cookie_value =  $cursor->fb_userName;   
            }else if(isset($cursor['tw_userName'])){
                $cookie_value =  $cursor->tw_userName;    
            }else if(isset($cursor['gp_userName'])){
                $cookie_value =  $cursor->gp_userName;  
            }

            $linkFolderImage = "user/noImg.jpg";
            if(isset($cursor->fb_avatar)){     
                $linkFolderImage = "user/fb/".$cursor->fb_id."/avatar/".$cursor->fb_avatar; 
            }else if(isset($cursor->tw_avatar)){
                $linkFolderImage = "user/tw/".$cursor->tw_id."/avatar/".$cursor->tw_avatar;
            }else if(isset($cursor->gp_avatar)){
                $linkFolderImage = "user/gp/".$cursor->gb_id."/avatar/".$cursor->gp_avatar;  
            }


            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $linkFolderImage;
               
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
            
            if(isset($cursor->hightScore) && $cursor->hightScore != null){
                //set highScore
                $cookie_highScore = "usScore";
                $cookie_value_score =  $cursor -> hightScore;
                setcookie($cookie_highScore, $cookie_value_score, time()+24*60*60, "/"); // 86400 = 1 day
            }
            
            //clear guest
            $date_of_expiry = time() - 60 ;
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );

            $jsonstring = json_encode($oldUser);
            echo $jsonstring;

        }else{
            //không có
            $msg = array('msg' => "login fails");
            $jsonstring = json_encode($msg);
            echo $jsonstring;
        }
        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }
?>
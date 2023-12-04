<?php
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        $oldUser = array();
        $userName = urldecode($_REQUEST["userName"]);
        // //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('cso');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('userName' => $userName));
        // if($cursor->count() == 0){
        //     //không có find other social name
        //     $cursor = $collection->find(array(
        //         '$or' => array(
        //             array("fb_userName" => $userName),
        //             array("tw_userName" => $userName),
        //             array("gp_userName" => $userName)
        //     )));
        //     if($cursor->count() > 0){
        //         foreach($cursor as $doc){
        //             $oldUser = $doc;
        //             break;
        //         }

        //         $jsonstring = json_encode($oldUser);
        //         echo $jsonstring;
        //     }else{
        //         $msg = array("msg" => "Dont have info");
        //         $jsonstring = json_encode($msg);
        //         echo $jsonstring;
        //     }
        // }else{
        //     //update info
        //     foreach($cursor as $doc){
        //         $oldUser = $doc;
        //         break;
        //     }
        //     $jsonstring = json_encode($oldUser);
        //     echo $jsonstring;
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager("mongodb://dinosaurgame.io");
        $filter = ['userName' => $userName ]; 
        //insert or update
        //$bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery("dino2.user", $query);
        $cursor = current($res->toArray());
        if($cursor){
            //update
            $cursor-> pwd = "";

            //save cookie if send skinId
        
            $jsonstring = json_encode($cursor);
            echo $jsonstring;
        }else{
            //không có
            $filter = [
                '$or'  => [
                  ['fb_userName' => $userName],
                  ['tw_userName' => $userName],
                  ['gp_userName' => $userName]
                ]
            ];
            $query = new MongoDB\Driver\Query($filter);
            $res = $mng->executeQuery("dino2.user", $query);
            $cursor = current($res->toArray());
            if($cursor){
                //update
                $jsonstring = json_encode($cursor);
                $cursor->pwd = "";            
                echo $jsonstring;
            }else{
                //save user name
                $cookie_name = "userInfo_name_guest";
                $cookie_value =   $userName;
                setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day

                $msg = array("msg" => "Dont have info !".$userName);
                $jsonstring = json_encode($msg);
                echo $jsonstring;
            }
        }
        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }
?>
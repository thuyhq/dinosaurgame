<?php
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        $configs = include('./../config.php');
        $public_link = $configs['public_link'];
        $url_mongodb = $configs['url_mongodb'];
        $databaseName = $configs['databaseName'];
        $oldUser = array();
        $userName = urldecode($_REQUEST["userName"]);
        $pwd = urldecode($_REQUEST["pwd"]);
        $email = urldecode($_REQUEST["email"]);

        // //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('cso');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('email' => $email));
        // if($cursor->count() == 0){
        //     //không có
        //     $jsonstring = json_encode($oldUser);
        //     echo $jsonstring;
        // }else{
        //     //update info
        //     foreach($cursor as $doc){
        //         $oldUser = $doc;
        //         break;
        //     }

        //     //update facebook info
        //     $oldUser["userName"] =  $userName;
        //     if($pwd != ""){
        //         $oldUser["pwd"] =  md5($pwd);
        //     }
        //     $collection->update(array("email" => $email),$oldUser);
            
        //     $cookie_name = "userInfo_name";
        //     $cookie_value =  $userName;

        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     $jsonstring = json_encode($oldUser);
        //     echo $jsonstring;
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager( $url_mongodb);
        $filter = ['email' => $email ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery($databaseName."user", $query);
        $cursor = current($res->toArray());
        if($cursor->email != NULL){
            $cursor->userName = $userName;
            $oldUser["userName"] =  $userName;
            //update
            if($pwd != ""){
                $oldUser["pwd"] =  md5($pwd);
                $bulk->update(
                    ['email' => $email], 
                    ['$set' =>  [
                                    'pwd' =>  md5($pwd),
                                    'userName' => $userName,
                                ]
                    ]
                );
            }else{
                $bulk->update(
                    ['email' => $email], 
                    ['$set' =>  [
                                    'userName' => $userName
                                ]
                    ]
                );
            }
            //excute mongodb command
            $mng->executeBulkWrite($databaseName.'user', $bulk);
            $cursor->pwd = "";
            $jsonstring = json_encode($cursor);
            echo $jsonstring;
        }else{
            //không có
            $jsonstring = json_encode($oldUser);
            echo $jsonstring;
        }
        
    }catch(\Exceptions $e){
        echo "<pre>"; print_r($e); echo "</pre>";
    }
?>
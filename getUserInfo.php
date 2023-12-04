<?php

    //get content success then save to mongoDB
    //conect mongoDB
    try{
        $configs = include('./../config.php');
        $public_link = $configs['public_link'];
        $url_mongodb = $configs['url_mongodb'];
        $databaseName = $configs['databaseName'];
        $oldUser = array();
        $skinId = intval(urldecode($_REQUEST["skinId"]));
        // //working on windhow
        $m = new MongoClient( $url_mongodb);
        $db = $m->selectDB($databaseName);
        $collection = new MongoCollection($db, 'equipment');
        $cursor = $collection->find(array('skinId' => $skinId));
        
        if($cursor->count() == 0){
            //không có
            $jsonstring = json_encode($oldUser);
            echo $jsonstring;
        }else{
            //update info
            foreach($cursor as $doc){
                $oldUser = $doc;
                break;
            }
            $jsonstring = json_encode($oldUser);
            echo $jsonstring;
        }

        // //working on ubuntu
        // $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
        // $filter = ['skinId' => $skinId ]; 
        // //insert or update
        // $bulk = new MongoDB\Driver\BulkWrite;
        // $query = new MongoDB\Driver\Query($filter);     
        
        // $res = $mng->executeQuery("cso.equipment", $query);
        // $cursor = current($res->toArray());
        // if($cursor){
        //     //update
        //     $jsonstring = json_encode($cursor);
        //     echo $jsonstring;
        // }else{
        //      //không có
        //      $jsonstring = json_encode($oldUser);
        //      echo $jsonstring;
        // }
        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }
?>
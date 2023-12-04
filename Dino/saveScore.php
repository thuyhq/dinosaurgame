<?php
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
		
		$data = file_get_contents('php://input');
		// var_dump($data);
		$obj = json_decode($data, false);
		$type = $obj->type;
        $userScore  = array(
			$type=> $obj->id,
            'userName'=> $obj->name,
            'score'=> $obj->score,
            'avatar' => $obj->avatar
        );

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
        // //insert new
        //$userScore -> _id = new MongoDB\BSON\ObjectID;
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($userScore);
        //excute mongodb command
        $mng->executeBulkWrite('dino.leaderboard', $bulk);

        //update sorce user
        $filter = [$type => $obj->id ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery("dino.user", $query);
        $cursor = current($res->toArray());

        if($cursor){
            if(isset($cursor->score) && ($cursor->score <  $obj->score)){
                $bulk->update(
                    [$type => $obj->id], 
                    ['$set' =>  [
                                    'score' =>   $obj->score
                                ]
                    ]
                );
                //excute mongodb command
                $isUpdate = true;
                $mng->executeBulkWrite('dino.user', $bulk);
            }else if(!isset($cursor->score)){
                $bulk->update(
                    [$type => $obj->id], 
                    ['$set' =>  [
                                    'score' =>   $obj->score
                                ]
                    ]
                );
                //excute mongodb command
                $isUpdate = true;
                $mng->executeBulkWrite('dino.user', $bulk);
            }
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
            $res = $mng->executeQuery("dino.user", $query);
            $cursor = current($res->toArray());
            if($cursor){
                //update
                if(isset($cursor->score) && ($cursor->score <  $obj->score)){
                    $bulk->update(
                        ['email' => $cursor->email], 
                        ['$set' =>  [
                                        'score' =>   $obj->score
                                    ]
                        ]
                    );
                    //excute mongodb command
                    $isUpdate = true;
                    $mng->executeBulkWrite('dino.user', $bulk);
                }else if(!isset($cursor->score)){
                    $bulk->update(
                        ['email' => $cursor->email], 
                        ['$set' =>  [
                                        'score' =>   $obj->score
                                    ]
                        ]
                    );
                    //excute mongodb command
                    $isUpdate = true;
                    $mng->executeBulkWrite('dino.user', $bulk);
                }
                
            }
        }
        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }

?>
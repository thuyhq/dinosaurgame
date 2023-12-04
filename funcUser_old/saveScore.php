<?php
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $userName = urldecode($_REQUEST["userName"]);
        $score = intval(urldecode($_REQUEST["score"]));
        $avatar = urldecode($_COOKIE['userInfo_avatar']);
        $isUpdate = false;

        $userScore  = array(
            'userName'=> $userName,
            'score'=> $score,
            'avatar' => $avatar
        );

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager("mongodb://dinosaurgame.io");
        // //insert new
        //$userScore -> _id = new MongoDB\BSON\ObjectID;
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($userScore);
        //excute mongodb command
        $mng->executeBulkWrite('dino2.leaderboard', $bulk);

        //update sorce user
        $filter = ['userName' => $userName ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery("dino2.user", $query);
        $cursor = current($res->toArray());

        if($cursor){
            if(isset($cursor->hightScore) && ($cursor->hightScore <  $score)){
                $bulk->update(
                    ['email' => $cursor->email], 
                    ['$set' =>  [
                                    'hightScore' =>   $score
                                ]
                    ]
                );
                //excute mongodb command
                $isUpdate = true;
                $mng->executeBulkWrite('dino2.user', $bulk);
            }else if(!isset($cursor->hightScore)){
                $bulk->update(
                    ['email' => $cursor->email], 
                    ['$set' =>  [
                                    'hightScore' =>   $score
                                ]
                    ]
                );
                //excute mongodb command
                $isUpdate = true;
                $mng->executeBulkWrite('dino2.user', $bulk);
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
            $res = $mng->executeQuery("dino2.user", $query);
            $cursor = current($res->toArray());
            if($cursor){
                //update
                if(isset($cursor->hightScore) && ($cursor->hightScore <  $score)){
                    $bulk->update(
                        ['email' => $cursor->email], 
                        ['$set' =>  [
                                        'hightScore' =>   $score
                                    ]
                        ]
                    );
                    //excute mongodb command
                    $isUpdate = true;
                    $mng->executeBulkWrite('dino2.user', $bulk);
                }else if(!isset($cursor->hightScore)){
                    $bulk->update(
                        ['email' => $cursor->email], 
                        ['$set' =>  [
                                        'hightScore' =>   $score
                                    ]
                        ]
                    );
                    //excute mongodb command
                    $isUpdate = true;
                    $mng->executeBulkWrite('dino2.user', $bulk);
                }
                
            }
        }

        if($isUpdate){
            $cookie_score = "usScore";
            $cookie_score_value =  $score;
            
            setcookie($cookie_score, $cookie_score_value, time()+24*60*60, "/"); // 86400 = 1 day
        }
        
    }catch(\Exceptions $e){
        $jsonstring = json_encode($e);
        echo $jsonstring;
    }

?>
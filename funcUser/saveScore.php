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
		//var_dump($_SERVER);die;
		$ip_request = get_client_ip();
		if(!isset($_SERVER['HTTP_REFERER'])) die;
		$referal_url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
		if($referal_url != "dinosaurgame.io") die;

		$request_data = file_get_contents('php://input');
		$jsDecoe = json_decode($request_data,false);
		
		$userName = strip_tags(urldecode($jsDecoe ->userName));
        $score = intval(urldecode($jsDecoe ->score));
		
		//$userName = strip_tags(urldecode($_REQUEST['userName']));
        //$score = intval(urldecode($_REQUEST['score']));
		
        $avatar = urldecode($_COOKIE['userInfo_avatar']);
        $isUpdate = false;
		
        $userScore  = array(
            'userName'=> $userName,
            'score'=> $score,
            'avatar' => $avatar,
			'time' => new MongoDB\BSON\UTCDateTime((new DateTime())->getTimestamp()*1000),
			'ip_request' => $ip_request
        );

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongodb);
        // //insert new
        //$userScore -> _id = new MongoDB\BSON\ObjectID;
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($userScore);
        //excute mongodb command
        $mng->executeBulkWrite($databaseName.'leaderboard', $bulk);

        //update sorce user
        $filter = ['userName' => $userName ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery($databaseName."user", $query);
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
                $mng->executeBulkWrite($databaseName.'user', $bulk);
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
                $mng->executeBulkWrite($databaseName.'user', $bulk);
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
            $res = $mng->executeQuery($databaseName."user", $query);
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
                    $mng->executeBulkWrite($databaseName.'user', $bulk);
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
                    $mng->executeBulkWrite($databaseName.'user', $bulk);
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

	function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
?>
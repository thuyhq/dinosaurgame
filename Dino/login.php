<?php
   try{
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
     $data = file_get_contents('php://input');
     // var_dump($data);
     $obj = json_decode($data, false);
     // var_dump($obj->name);
	 $linkReturnImage = "/user/";
     $avatarName = "noImg".".jpg";
	 $file_return_name = $linkReturnImage.$avatarName;
     // var_dump($userInfo);
     // die;
     $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
     $filter = [
                '$and' => [
                    ['user_name'=> $obj->user_name],
                    ['pw' => md5($obj->pw)]
                ]
            ];
     //insert or update
     $bulk = new MongoDB\Driver\BulkWrite;
     $query = new MongoDB\Driver\Query($filter);

     $res = $mng->executeQuery("dino.user", $query);
     $cursor = current($res->toArray());
     if($cursor) {
		 $return = array(
         'success'=> true,
		 'url'=> 'http://45.33.124.160/Dino'.$file_return_name,
		 'user_name' => $obj->user_name,
		 'highScore' => $cursor->score
		);
         echo json_encode($return);
     }
     else {
		 $return = array(
		 'success'=> false,
		 'msg'=> 'User name not exist or password incorrect!'
		);
		$jsonstring = json_encode($return);
		echo $jsonstring;
     }
	 
   } catch(Exceptions $e) {
     echo $e.getMessage();
   }
   exit();
?>

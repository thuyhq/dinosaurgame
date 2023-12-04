<?php
   try{
     $data = file_get_contents('php://input');
     // var_dump($data);
     $obj = json_decode($data, false);
     // var_dump($obj->name);
	 $linkReturnImage = "/user/";
     $avatarName = "noImg".".jpg";
	 $file_return_name = $linkReturnImage.$avatarName;
	 $highScore = 0;
     $userInfo = array(
         'user_name'=> $obj->user_name,
		 'name'=> $obj->user_name,
         'avatar'=> $avatarName,
         'pw' => md5($obj->pw),
		 'score' => $highScore
     );
     // var_dump($userInfo);
     // die;
     $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
     $filter = ['user_name' => $obj->user_name ];
     //insert or update
     $bulk = new MongoDB\Driver\BulkWrite;
     $query = new MongoDB\Driver\Query($filter);

     $res = $mng->executeQuery("dino.user", $query);
     $cursor = current($res->toArray());
     if($cursor) {
		 $return = array(
         'success'=> false,
         'msg'=> 'Your user name exist'
		);
         echo json_encode($return);
     }
     else {
         //insert new
         $userInfo -> _id = new MongoDB\BSON\ObjectID;
         $bulk->insert($userInfo);
         //excute mongodb command
         $mng->executeBulkWrite('dino.user', $bulk);
		 $return = array(
		 'success'=> true,
		 'name'=> $obj->user_name,
         'url'=> 'http://45.33.124.160/Dino'.$file_return_name,
		 'score' => $highScore
		);
		$jsonstring = json_encode($return);
		echo $jsonstring;
     }
	 
   } catch(Exceptions $e) {
     echo $e.getMessage();
   }
   exit();
?>

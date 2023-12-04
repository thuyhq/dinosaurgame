<?php
   try{
     $data = file_get_contents('php://input');
     // var_dump($data);
     $obj = json_decode($data, false);
     // var_dump($obj->name);

     $image_link = $obj->url;
     $linkFolderImage = "./user/tw/".$obj->id."/avatar/";
	 $linkReturnImage = "/user/tw/".$obj->id."/avatar/";
     $avatarName = "tw_avatar".".jpg";
     if (!file_exists($linkFolderImage)) {
          mkdir($linkFolderImage, 0777, true);
     	    echo 'No folder';
     }
     $file_name = $linkFolderImage.$avatarName;
	 $file_return_name = $linkReturnImage.$avatarName;
     $content = file_get_contents($image_link);
     file_put_contents($file_name, $content);
	 $highScore = 0;
     $userInfo = array(
         'email'=> $obj->email,
         'tw_id'=> $obj->id,
         'tw_avatar'=> $avatarName,
         'tw_userName' => $obj->name,
         'avatar' => $file_name,
		 'score' => $highScore
     );
     // var_dump($userInfo);
     // die;
     $mng = new MongoDB\Driver\Manager("mongodb://45.33.124.160");
     $filter = ['tw_id' => $obj->id ];
     //insert or update
     $bulk = new MongoDB\Driver\BulkWrite;
     $query = new MongoDB\Driver\Query($filter);

     $res = $mng->executeQuery("dino.user", $query);
     $cursor = current($res->toArray());
     if($cursor){
         //update
         if($cursor -> tw_id != NULL){
             $bulk->update(
                         ['email' => $obj->email],
                         ['$set' =>  [
                                         'tw_id' => $obj->fb_id,
                                         'tw_avatar' =>  $avatarName,
                                         'tw_userName' => $obj->fb_name
                                     ]
                         ]
                     );
             //excute mongodb command
             $mng->executeBulkWrite('dino.user', $bulk);
         }
		 $highScore = $cursor -> score;
     }
     else {
         //insert new
         $userInfo -> _id = new MongoDB\BSON\ObjectID;
         $bulk->insert($userInfo);
         //excute mongodb command
         $mng->executeBulkWrite('dino.user', $bulk);
     }
	 $return = array(
         'highScore'=> $highScore,
         'url'=> 'http://45.33.124.160/Dino'.$file_return_name
     );
	 $jsonstring = json_encode($return);
     echo $jsonstring;
   } catch(Exceptions $e) {
     echo $e.getMessage();
   }
   exit();
?>

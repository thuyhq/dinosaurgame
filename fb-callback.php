<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    require_once __DIR__ . '/Facebook/autoload.php'; // change path as needed
    $configs = include('./config.php');
    $public_link = $configs['public_link'];
    $url_mongo = $configs['url_mongo'];
    $databaseName = $configs['databaseName'];

    //echo $public_link;
    // error_reporting(E_ALL);
    // ini_set('display_errors', TRUE);
    // ini_set('display_startup_errors', TRUE);

    $fb = new Facebook\Facebook([
        'app_id' => '662647887110255', // Replace {app-id} with your app id
        'app_secret' => '0f87febbd09acecb5fd88dbce0d5df57',
        'default_graph_version' => 'v2.2',
    ]);
    $date_of_expiry = time() - 60 ;
    $helper = $fb->getRedirectLoginHelper(); 

    if (isset($_GET['state'])) { 
        $helper->getPersistentDataHandler()->set('state', $_GET['state']); 
    }

    //get image from user
    /* PHP SDK v5.0.0 */
    /* make the API call */
    // https://graph.facebook.com/USER_ID/picture?width=590&height=600
    try {
        // Returns a `Facebook\FacebookResponse` object
        $accessToken = $helper->getAccessToken();
        $response = $fb->get('me?fields=picture,email,first_name,last_name',$accessToken);
        $graphNode = $response->getGraphNode();
        //get image
        $userPicUrl = $fb->get('/'.$graphNode["id"].'/picture?type=large',$accessToken)->getHeaders();
        //Get the file
        $image_link = $userPicUrl["Location"];
        $split_image = pathinfo(parse_url($image_link)['path']);

        $linkFolderImage = "./user/fb/".$graphNode["id"]."/avatar/";

        if (!file_exists($linkFolderImage)) {
            mkdir($linkFolderImage, 0777, true);
        }
        if(isset($split_image['extension'])){
            $avatarName = "fb_avatar".".".$split_image['extension'];
        }else{
            $avatarName = "fb_avatar".".jpg";
        }
        
        $file_name = $linkFolderImage.$avatarName;

        $content = file_get_contents($image_link);
        file_put_contents($file_name, $content);

    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
   

    if (! isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }

    
    /* handle the result */
    
    // image
    // echo '<h3>Images</h3>';
    // var_dump($graphNode["email"]);
    // // Logged in
    // echo '<h3>Access Token</h3>';
    // var_dump($accessToken->getValue());

    // The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

    // Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    // echo '<h3>Metadata</h3>';
    // var_dump($tokenMetadata);

    // Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId('662647887110255'); // Replace {app-id} with your app id
    // If you know the user ID this access token belongs to, you can validate it here
    //$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();

    if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
            exit;
        }
        echo '<h3>Long-lived</h3>';
        var_dump($accessToken->getValue());
    }
    
    //get content success then save to mongoDB
    //conect mongoDB
    try{
        $userInfo = array(
            'email'=> $graphNode["email"],
            'fb_id'=> $graphNode["id"],
            'fb_avatar'=> $avatarName,
            'fb_userName' => $graphNode["last_name"] .' '. $graphNode["first_name"],
            'avatar' => $file_name
        );

        // //working on windhow
        // $m = new MongoClient("mongodb://45.33.124.160");
        // $db = $m->selectDB('Dino');
        // $collection = new MongoCollection($db, 'user');
        // $cursor = $collection->find(array('email' => $graphNode["email"]));
        // if($cursor->count() == 0){
        //     //insert new
        //     $collection->insert($userInfo);
        //     // //Save to session
        //     //$_SESSION['userInfo_name'] =  $userInfo["userName"];
        //     $cookie_name = "userInfo_name";
        //     $cookie_value =  $userInfo -> $userInfo["fb_userName"];

        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];

        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
        // }else{
        //     //update info
        //     foreach($cursor as $doc){
        //         $oldUser = $doc;
        //         break;
        //     }
        //     //update facebook info
        //     if($oldUser["fb_id"] == NULL){
        //         $oldUser["fb_id"] = $graphNode["id"];
        //         $oldUser["fb_avatar"] = $avatarName;
        //     }
        //     $collection->update(array("_id" => new MongoId($oldUser["_id"])),$oldUser);
        //     // //Save to session
        //     //$_SESSION['userInfo_name'] =  $userInfo["userName"];
        //     $cookie_name = "userInfo_name";
        //     if(isset($oldUser["userName"])){
        //         //changed userName
        //         $cookie_value =  $oldUser["userName"];
        //     }else{
        //         $cookie_value =  $oldUser["fb_userName"];
        //     }
            
        //     $cookie_name_avatar = "userInfo_avatar";
        //     $cookie_value_avatar =  $userInfo -> $public_link.$userInfo["avatar"];
            
        //     setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
        //     setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
        // }

        // //working on ubuntu
        $mng = new MongoDB\Driver\Manager($url_mongo);
        $filter = ['email' => $graphNode["email"] ]; 
        //insert or update
        $bulk = new MongoDB\Driver\BulkWrite;
        $query = new MongoDB\Driver\Query($filter);     
        
        $res = $mng->executeQuery($databaseName."user", $query);
        $cursor = current($res->toArray());
        if($cursor){
            //update
            if($cursor -> fb_id == NULL){
                $bulk->update(
                            ['email' => $graphNode["email"]], 
                            ['$set' =>  [
                                            'fb_id' => $graphNode["id"],
                                            'fb_avatar' =>  $avatarName,
                                            'fb_userName' => $graphNode["last_name"] .' '. $graphNode["first_name"]
                                        ]
                            ]
                        );
                //excute mongodb command
                $mng->executeBulkWrite($databaseName.'user', $bulk);
            }

            $usName = $cursor ->userName;
            $_SESSION["userInfo_name"] =  $cursor ->userName;
            $cookie_name = "userInfo_name";
            if($usName != NULL){
                $cookie_value =  $usName;
            }else{
                $cookie_value =  $userInfo['fb_userName'];
            }

            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo["avatar"];

            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day
            
            if($cursor -> hightScore != null){
                //set highScore
                $cookie_highScore = "usScore";
                $cookie_value_score =  $cursor -> hightScore;
                setcookie($cookie_highScore, $cookie_value_score, time()+24*60*60, "/"); // 86400 = 1 day
            }

            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
            
            //echo "<script>location.hash = 'index'</script>";
        }else{
            //insert new
            $userInfo -> _id = new MongoDB\BSON\ObjectID;
            $bulk->insert($userInfo);
            //excute mongodb command
            $mng->executeBulkWrite($databaseName.'user', $bulk);
            // //Save to session
            $_SESSION["userInfo_name"] =  $userInfo['fb_userName'];
            $cookie_name = "userInfo_name";
            $cookie_value =  $userInfo['fb_userName'];
        
            $cookie_name_avatar = "userInfo_avatar";
            $cookie_value_avatar =  $userInfo["avatar"];
               
            setcookie($cookie_name, $cookie_value, time()+24*60*60, "/"); // 86400 = 1 day
            setcookie($cookie_name_avatar, $cookie_value_avatar, time()+24*60*60, "/"); // 86400 = 1 day

            //clear guest
            setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
            
            //echo "<script>location.hash = 'index'</script>";
        }

        $msg = array(
                'msg' => "login success",
                'success' => true
            );
        $jsonstring = json_encode($msg);
        echo $jsonstring;
        //header('Location: '.$public_link.'#index');
        //echo "<script> location = 'http://conquersky.io/#index'</script>";
    }catch(\Exceptions $e){
        echo "<pre>"; print_r($e); echo "</pre>";
    }
    //$public_link    // User is logged in with a long-lived access token.
    // You can redirect them to a members-only page.
    //header('Location: https://example.com/members.php');

    exit();
?>


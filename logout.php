<?php
    $date_of_expiry = time() - 60 ;
    setcookie( "userInfo_name", "anonymous", $date_of_expiry, "/" );
    setcookie( "userInfo_avatar", "anonymous", $date_of_expiry, "/" );
    setcookie( "skinID", "anonymous", $date_of_expiry, "/" );
    setcookie( "userInfo_name_guest", "anonymous", $date_of_expiry, "/" );
    setcookie( "usScore", "anonymous", $date_of_expiry, "/" );
    $configs = include('./config.php');
    $public_link = $configs['public_link'];

    //header('Location: '.$public_link);
    
    $msg = array('msg' => "logout success");
    $jsonstring = json_encode($msg);
    echo $jsonstring;

?>
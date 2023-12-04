<?php
    require_once __DIR__ . '/Facebook/autoload.php'; // change path as needed
    require_once  __DIR__ . '/twitteroauth/autoload.php';
    require_once __DIR__ . '/google-api-php-client/vendor/autoload.php';
    use Abraham\TwitterOAuth\TwitterOAuth;

    $configs = include('./config.php');
    $public_link = $configs['public_link'];
    session_start();


    //login with facebook
    $fb = new Facebook\Facebook([
        'app_id' => '662647887110255', 
        'app_secret' => '0f87febbd09acecb5fd88dbce0d5df57',
        'default_graph_version' => 'v2.2',
        ]);
      
    // echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
    
    //get call back url

    $callBackUrl = $_GET['type'];
    if($callBackUrl != "" && $callBackUrl == $public_link."fb-callback.php"){
        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email','public_profile']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($callBackUrl, $permissions);
        callUrl($loginUrl);
    }else if($callBackUrl != "" && $callBackUrl == $public_link."tw-callback.php"){
        
        try{
            // create TwitterOAuth object
            $twitteroauth = new TwitterOAuth($configs['consumer_key'], $configs['consumer_secret']);
            // request token of application
            $request_token = $twitteroauth->oauth(
                'oauth/request_token', [
                    'oauth_callback' => $callBackUrl
                ]
            );
            // throw exception if something gone wrong
            if($twitteroauth->getLastHttpCode() != 200) {
                throw new \Exception('There was a problem performing this request');
            }

            // save token of application to session
            //$_SESSION['oauth_token'] = $request_token['oauth_token'];
            //$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            $cookie_name_ot = "oauth_token";
            $cookie_value_ot =  $request_token['oauth_token'];
            
            $cookie_name_ots = "oauth_token_secret";
            $cookie_value_ots =  $request_token['oauth_token_secret'];

            setcookie($cookie_name_ot, $cookie_value_ot, time()+60,"/"); // 86400 = 1 day
            setcookie($cookie_name_ots, $cookie_value_ots, time()+60,"/"); // 86400 = 1 day

            // generate the URL to make request to authorize our application
            $url = $twitteroauth->url(
                'oauth/authorize', [
                    'oauth_token' => $request_token['oauth_token']
                ]
            );
            // and redirect
            header('Location: '. $url);
        }catch(\Exception $e){
            echo "<pre>"; print_r($e); echo "</pre>";
        }
        

    }else if($callBackUrl != "" && $callBackUrl == $public_link."ggp-callback.php"){
        
        $client = new Google_Client();
        $client->setAuthConfigFile('google-api-php-client/vendor/client_credentials.json');
        //$client->setRedirectUri($callBackUrl);

        $client->addScope('profile');
        $client->addScope('https://www.googleapis.com/auth/contacts.readonly');
        $client->addScope('https://www.googleapis.com/auth/userinfo.email');
        $client->addScope('https://www.googleapis.com/auth/userinfo.profile');

        if (!isset($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        } else {
           
            $client->authenticate($_GET['code']);
            //$_SESSION['access_token'] = $client->getAccessToken();
            $redirect_uri = $callBackUrl;
            //var_dump($redirect_uri);
            //die;
            header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
        }
    }
    //swith case type 

    function callUrl($url){
        header('Location: '.$url);
    }

?>
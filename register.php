<div class="M3">
    <div class="M4">
        <p id="mgs_alter_exist" class="alert alert-error hidden">Error: Your email exist !</p>
        <div class="menu" style="position: relative">
            <div class="COH">
                <div class="menu-control">
                    <a class="btn-lg btn-block btn-social btn-facebook" href="javascript:void(0);" onclick="fbLogin()">
                        <span class="fa fa-facebook"></span> Sign in with Facebook
                    </a>
                    <a class="btn-lg btn-block btn-social btn-twitter" href="javascript:void(0);" onclick="twitterAccess()">
                        <span class="fa fa-twitter"></span> Sign in with Twitter
                    </a>
                    <a class="btn-lg btn-block btn-social btn-google" href="javascript:void(0);" onclick="gpAccess()">
                        <span class="fa fa-google"></span> Sign in with Google+
                    </a>
                </div>
            </div>
            <div id="menu-right-banner-pc" class="COH">
                <form action="" method="POST" id="register_frm">
                    <div class="menu-control">
                        <input type="text" id="regis_email" name="email" class="input input-regsiter input-h46" placeholder="email" value="" maxlength="50" />
                        <input type="password" id="regis_pwd" name="password" class="input input-regsiter input-h46" placeholder="password" value="" autofocus id="input-password"
                        />
                    </div>
                    <div class="menu-control menu-login">
                        <div class="clear"></div>
                    </div>
                    <div class="menu-control">
                        <button type="button" onclick="register()" class="btn-block btn-lg btn-danger">Register</button>
                    </div>
                </form>
                <div class="menu-control text-link text-center" style="width: 300px; padding: 10px 0!important;">
                    <?php
                    echo('
                    <a onclick="return clickChangeHash('.'\'login\''.');" title="register" class="btn-link" style="color:#337ab7;">
                        Back to login
                    </a>')
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
       
<div class="M3">
    <div class="M4">
        <p id="mgs_alter" class="alert alert-error hidden">Error: Invalid Email or Password</p>
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
                <div class="party-group">
                    <div class="party-group-content" style="display:flex">
                        <div class="party-text">Enter your email</div>
                        <input id="email" type="text" class="input-link party-link" placeholder="Submit your email" value="">
                        <button id="submit-reset" type="button" class="btn-md btn-success" value="send" onclick="resetpassword();">Submit</button>
                        <a class="party-text" onclick="hidePopupEmail();" style="text-indent: 0;">Back</a>
                        <script type="text/javascript">
                            function resetpassword() {
                                var email = $('#email').val();
                                if (!email) {
                                    alert('Please enter your email');
                                    return;
                                }
                                var data = { email: email };
                                // $.ajax({
                                //     url: "http://flaap.io/user/forgot.php",
                                //     method: "POST",
                                //     data: data,
                                //     dataType: "json",
                                //     beforeSend: function () {
                                //         $('#submit-reset').val('sending...')
                                //     },
                                //     success: function (ress) {
                                //         if (!ress.status) {
                                //             alert('Sorry, operation failed, please try again');
                                //         } else {
                                //             alert('Congrat!!! The password-setting path has been sent to your email');
                                //             $('#email').val('');
                                //             FuncLogin.act.hide();
                                //         }
                                //     },
                                //     complete: function () {
                                //         $('#submit-reset').val('send')
                                //     },
                                //     error: function (msg) {
                                //         console.log(msg);
                                //     },
                                // });
                            }
                        </script>
                    </div>
                </div>
            </div>
            <div id="menu-right-banner-pc" class="COH">
                <form>
                    <div class="menu-control">
                        <input id="user_email" type="text" name="email" class="input input-regsiter input-h46" placeholder="email" value=""  maxlength="50" />
                        <input id="user_passs" type="password" name="password" class="input input-regsiter input-h46" placeholder="password" value="" id="input-password"
                        />
                    </div>
                    <div class="menu-control menu-login">
                        <input type="checkbox" class="float-left" value="1" name="remember" checked="" />
                        <a class="btn-link float-left btn-rmb">Remember me</a>
                        <a href="javascript:void(0);" onclick="showPopupEmail();" title="Forgot password" class="btn-link float-right btn-fgp">
                            Forgot password?
                        </a>
                        <div class="clear"></div>
                    </div>
                    <div class="menu-control">
                        <button type="button" onclick="submitFunc()" class="btn-block btn-lg btn-danger">Login</button>
                    </div>
                </form>
                <div class="menu-control text-link text-center" style="padding-left: 20px; padding-right:10px;">
                    <a onclick="return clickChangeHash('index');" title="back" class="btn-link float-left" >
                            Back
                    </a>
                    <a onclick="return clickChangeHash('register');" title="register" class="btn-link float-right">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
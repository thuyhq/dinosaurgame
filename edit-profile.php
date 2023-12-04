<div class="M3">
    <div class="M4">
        <div class="menu">
            <div class="COH">
                <div class="menu-control" style="width: 230px; display:none;">
                    <div class="w150">
                        <?php
                            if(isset($_SESSION["test"])){
                                echo("test   ". $_SESSION["test"]);
                            }
                            if(isset($_SESSION["test2"])){
                                echo("test2   ". $_SESSION["test2"]);
                            }
                            if(isset($_SESSION["test3"])){
                                echo("test3   ". $_SESSION["test3"]);
                            }
                            $avtarUrl = 'user/noImg.jpg';
                            if(isset($_COOKIE["userInfo_avatar"])){
                                $avtarUrl = $_COOKIE["userInfo_avatar"];
                            }
                            echo('
                                <div class="frame">
                                    <img id="user-avatar" src="'.$public_link.$avtarUrl.'" alt="" class="content-image" />
                                </div>
                                <button class="btn btn-success" onclick="Func.act.select();">Change avatar</button>
                            ');
                            
                        ?>
                    </div>
                </div>
            </div>
            <div id="menu-right-banner-pc" class="COH" style="color:white;">
                <form>
                    <div class="menu-control">
                        <label class="input-label">Email:</label>
                        <input id="user_email_edit" type="text" name="email" value="" placeholder="Your Email" disabled="" style="background-color: #eee;"
                            class="input input-regsiter input-h36" />
                        <label class="input-label">Nickname:</label>
                        <input id="user_nickname_edit" type="text" name="nickname" value="" placeholder="Your Nickname" class="input input-regsiter input-h36"
                        />
                        <label class="input-label">New password:</label>
                        <input id="user_passs_edit" type="password" name="password" value="" placeholder="Your Password" class="input input-regsiter input-h36" autofocus
                        />
                    </div>
                    <div class="party-group">
                        <div style="width: 264px; height: 375px; background-color: white; padding-left: 10px" class="image-editor">
                            <div style="margin-left: 249px">
                                <img onclick="Func.act.hide()" src="http://flaap.io/theme/img/avatar_close.png" />
                            </div>
                            <div style="color: red; font-size: 15px; font-family: none" id="show-error">
                                <div id="err-size" class="hide" style="color: red; font-size: 15px; font-family: none">
                                    Image max size is 2Mb
                                    <br />
                                </div>
                                <div id="err-min" class="hide" style="color: red; font-size: 15px; font-family: none">
                                    Image must over 50x50px
                                    <br />
                                </div>
                                <div id="err-max" class="hide" style="color: red; font-size: 15px; font-family: none">
                                    Image cannot over 1500x1500px!
                                    <br />
                                </div>
                            </div>
                            <input style="display: none" id="avatar-file" type="file" class="cropit-image-input">
                            <div class="cropit-preview"></div>
                            <input style="margin-top: 5px" type="range" class="cropit-image-zoom-input">
                            <div id="select2-avatar-place">
                                <input id="select2-avatar" style="width: 250px; margin-top: 5px" type="button" onclick="Func.act.select()" value="Select Other">
                            </div>
                            <div>
                                <input id="save-avatar" style="width: 250px; margin-top: 5px" type="button" onclick="Func.act.saveAvatar()" value="Save">
                            </div>
                        </div>
                    </div>
                    <div class="menu-control">
                        <button type="button" onclick="submitForm()" class="btn btn-submit"><b>Submit</b></button>
                        <a  onclick="return clickChangeHash('index');" class="party-text back-btn" style="color:#572d00;"><b>Back to home</b></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    


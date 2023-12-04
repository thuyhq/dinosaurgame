<?php
// $tab is set in the file that includes this
if ($tab == null) $tab = 'index';

?>


<div class ="tabPage" id="indexTab" <?php if ($tab != 'index') : ?> style="display:none" <?php endif ?> >
    <?php if ($tab == 0 || $tab == 'index') : ?> 
    <?php include 'indexTab.php' ?>
    <?php endif ?>
</div>

<div class ="tabPage" id="loginTab" <?php if ($tab != 'login') : ?> style="display:none"<?php endif ?>>
    <?php if ($tab == 0 || $tab == 'login') : ?> 
        <?php include 'login.php' ?>
    <?php endif ?> 
</div>

<div class ="tabPage" id="registerTab" <?php if ($tab != 'register') : ?> style="display:none"<?php endif ?>>
    <?php if ($tab == 0 || $tab == 'register') : ?>
        <?php include 'register.php' ?>
    <?php endif ?>
</div>  

<div class ="tabPage" id="leaderboardTab" <?php if ($tab != 'leaderboard') : ?> style="display:none"<?php endif ?>>
    <?php if ($tab == 0 || $tab == 'leaderboard') : ?>
        <?php include 'funcUser/leader-board.php' ?>
    <?php endif ?>
</div>  

<div class ="tabPage" id="editProfileTab" <?php if ($tab != 'editProfile') : ?> style="display:none"<?php endif ?>>
    <?php if ($tab == 0 || $tab == 'leaderboard') : ?>
        <?php include 'edit-profile.php' ?>
    <?php endif ?>
</div>  

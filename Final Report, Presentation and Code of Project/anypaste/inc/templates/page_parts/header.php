<header class="mdl-layout__header mdl-layout__header--scroll mdl-shadow--2dp">
        <div class="mdl-layout__header-row">
          <!-- Title -->
          <span class="mdl-layout-title"><a href="<?php echo Config::get('appinfo/baseURL'); ?>" style="color: #fff;"><?php echo Config::get('appinfo/title'); ?></a></span>
          <!-- Add spacer, to align navigation to the right -->
          <div class="mdl-layout-spacer"></div>
          <!-- Navigation -->
          <nav class="mdl-navigation">
            <a class="mdl-navigation__link" href=""></a>
            <a class="mdl-navigation__link" href=""></a>
            <a class="mdl-navigation__link" href=""></a>
            <a class="mdl-navigation__link" href=""></a>
          </nav>
        </div>
      </header>
<?php
if($user->isLoggedIn()) {
?>
      <div class="mdl-layout__drawer">
        <span class="mdl-layout-title"><?php echo $user->data()->name; ?></span>
        <nav class="mdl-navigation">
          <a class="mdl-navigation__link" href="<?php echo Config::get('appinfo/baseURL'); ?>profile.php?user=<?php echo $user->data()->username; ?>">Profile</a>
          <a class="mdl-navigation__link" href="<?php echo Config::get('appinfo/baseURL'); ?>changepassword.php">Change Password</a>
          <a class="mdl-navigation__link" href="<?php echo Config::get('appinfo/baseURL'); ?>logout.php">Logout</a>
        </nav>
      </div>
<?php
}else{
?>
      <div class="mdl-layout__drawer">
        <span class="mdl-layout-title"><?php echo Config::get('appinfo/title'); ?></span>
        <nav class="mdl-navigation">
          <a class="mdl-navigation__link" href="<?php echo Config::get('appinfo/baseURL'); ?>login.php">Login</a>
          <a class="mdl-navigation__link" href="<?php echo Config::get('appinfo/baseURL'); ?>register.php">Register</a>
        </nav>
      </div>

<?php
}
?>
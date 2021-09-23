<?php
require_once 'corex/autoload.php';


if(!$paste_url = Form::data('p')) {
  Redirect::to('index.php');
}else{

    $paste_data_ob = DB::operation()->query('SELECT * FROM paste_data WHERE content_type = 1 AND url = ?', array($paste_url));
    if($paste_data_ob->count()){
      foreach ($paste_data_ob->results() as $got_paste) {
        $echo_paste = $got_paste->content;
        $paste_syntax = $got_paste->syntax;
        $paste_title = $got_paste->title;
        $paste_date = $got_paste->creation_time;
        $paster = $got_paste->user_id;
        $isPrivate = $got_paste->isPrivate;
      }
    }
  }

?>

<html>
  <head>
    <title><?php echo $paste_title.' - '; echo Config::get('appinfo/title'); ?></title>
    <!-- Material Design Lite -->
    <script src="js/material.min.js"></script>
    <link rel="stylesheet" href="<?php echo Config::get('appinfo/baseURL'); ?>css/material.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('appinfo/baseURL'); ?>css/index.css">
    <!-- Folder effect -->
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('appinfo/baseURL'); ?>css/demo.css" />
    <!-- Prism Highlight -->
    <link rel='stylesheet' href='<?php echo Config::get('appinfo/baseURL'); ?>css/prism.min.css'>
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('appinfo/baseURL'); ?>css/view.css" />
    <script>
      document.documentElement.className = 'js';
      function browserCanUseCssVariables() { return window.CSS && window.CSS.supports && window.CSS.supports('--fake-var', 0); }
      if (!browserCanUseCssVariables()) alert('Your browser does not support CSS Variables. Please use a modern browser to view this demo.');
    </script>
    <style type="text/css">
      pre .cljs-copy-btn {
          padding: 0;
          width: 3.5em;
          height: 2em;
          color: #ddd;
          border: none;
          outline: none;
          cursor: pointer;
          font-size: .85em;
          border-radius: 10px;
          -webkit-transition: background .5s;
          transition: background .5s;
          background-color: #20b7a4;
      }
    </style>
  </head>
  <body>


<?php

if(Session::exists('home')){
  /*echo Session::flash('home');*/
?>

<div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
  <div class="mdl-snackbar__text"></div>
  <button class="mdl-snackbar__action" type="button"></button>
</div> 

<?php
}

$user = new User();

//check if the user is logged in, and show protected content

?>
    <!-- Uses a header that scrolls with the text, rather than staying
      locked at the top -->
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">

<?php require 'inc/templates/page_parts/header.php' ?>






<div class="border_padding">
  <div class="demo-card-wide" style="width: 100%">
        <h2 class="mdl-card__title-text" style="padding-left: 25px;">
          <i class="material-icons">format_align_right</i>
          <?php echo $paste_title; ?></h2>
      <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--4-col">
          <span class="mdl-chip mdl-chip--deletable mdl-button--raised mdl-js-ripple-effect mdl-button--primary" style="<?php if ($paster == "0") {
            echo "background-color: #f07695";
          } else{ echo "background-color: #20b7a4"; } ?>">
              <span class="mdl-chip__text"><?php echo $paster != "0" ? $paster : "Unknown"; ?></span>
              <button type="button" class="mdl-chip__action"><i class="material-icons" style="color: #fff;">account_circle</i></button>
          </span>
          <span class="mdl-chip mdl-chip--deletable mdl-button--raised">
              <span class="mdl-chip__text"><?php echo $paste_date; ?></span>
              <button type="button" class="mdl-chip__action"><i class="material-icons">date_range</i></button>
          </span>
          <span class="mdl-chip mdl-chip--deletable mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
              <span class="mdl-chip__text"><?php echo $paste_syntax; ?></span>
              <button type="button" class="mdl-chip__action"><i class="material-icons">code</i></button>
          </span>
        </div>
        <div class="mdl-cell mdl-cell--4-col"></div>
        <div class="mdl-cell mdl-cell--4-col"></div>
      </div>
  </div>
</div>







<?php
if($user->isLoggedIn()){
  $username_from_user_table = $user->data()->username;
}else{
  $username_from_user_table = '';
}

 if ($isPrivate != '1' || $paster == $username_from_user_table) {
  /*Increase the hits nummber by 1*/ 
  DB::operation()->query('UPDATE paste_data SET hits = hits + 1 WHERE url = ?', array($paste_url));
  ?>
            <pre style="width: 100%; max-width: 100%"><code class="language-<?php echo $paste_syntax; ?>">
<?php echo $echo_paste; ?>
          </code></pre>
<?php }else{ ?>
    <div class="mdl-grid">
      <div class="mdl-layout-spacer"></div>
      <div class="mdl-cell mdl-cell--12-col">
        <div class="demo-card-square mdl-card mdl-shadow--2dp">
          <div class="mdl-card__title mdl-card--expand">
            <h2 class="mdl-card__title-text">Private</h2>
          </div>
          <div class="mdl-card__supporting-text">
            This is a private paste! You do not have the permission to view the paste!
          </div>
          <div class="mdl-card__actions mdl-card--border">
            <a href="<?php echo Config::get('appinfo/baseURL') ?>" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
              Back to Home
            </a>
          </div>
        </div>
      </div>
      <div class="mdl-layout-spacer"></div>
    </div>
<?php } ?>



    </div>
<script type="text/javascript">
  r(function(){
    var snackbarContainer = document.querySelector('#demo-toast-example');
    var data = { message: <?php if(Session::exists('home')){ echo "'"; echo Session::flash('home'); echo "'";} ?> };
    snackbarContainer.MaterialSnackbar.showSnackbar(data);
});
function r(f){ /in/.test(document.readyState)?setTimeout('r('+f+')',9):f()}
</script>

    <script src="<?php echo Config::get('appinfo/baseURL'); ?>js/anime.min.js"></script>
    <script src="<?php echo Config::get('appinfo/baseURL'); ?>js/charming.min.js"></script>
    <script src="<?php echo Config::get('appinfo/baseURL'); ?>js/main.js"></script>
    <script>
    (function() {
      new DurgaFx(document.querySelector('.content--durga .folder--effect1'));
      new DurgaFx(document.querySelector('.content--durga .folder--effect2'));
      new DurgaFx(document.querySelector('.content--durga .folder--effect3'));
      new DurgaFx(document.querySelector('.content--durga .folder--effect4'));
    })();
    </script>


    <!-- Prism Highlight -->
    <script src='<?php echo Config::get('appinfo/baseURL'); ?>js/codeline.js'></script>
    <script src='<?php echo Config::get('appinfo/baseURL'); ?>js/prism.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/prism/1.9.0/components/prism-<?php echo $paste_syntax; ?>.js'></script>
    <script  src="<?php echo Config::get('appinfo/baseURL'); ?>js/prism.js"></script>

  </body>
</html>
<?php
require_once 'corex/autoload.php';

/*Paste Saving in DB code*/
$response = array();
if(Form::exists()) {
  // if (Token::check(Form::data('token'))) {

    $form = new Validate();
    $validation = $form->check($_POST, array(
        'title' => array(
            'required' => true,
            'min' => 3
          ),
        'content' => array(
            'required' => true
          ),
        'syntax' => array(
            'required' => true
          ),
        'url' => array(
            'required' => true,
            'min' => 5,
            'unique' => 'paste_data' //here 'users' is  the  table name. and field name is same to the HTML form's field
          )
      ));

    if ($validation->passed()) {
      $userOB = new User();
      if($userOB->isLoggedIn()){
        $uid = $userOB->data()->username;
        $isPrivate = Form::data('isPrivate') == 'on' ? 1 : 0;
      }else{
        $uid = 0;
        $isPrivate = 0;
      }
      try{
        $insert_paste = DB::operation()->insert('paste_data', array(
          'content_type' => 1,
          'title' => Form::data('title'),
          'content'     => htmlspecialchars(Form::data('content')),
          'syntax'     => Form::data('syntax'),
          'creation_time'   => date('Y-m-d H:i:s'),
          'url' => Form::data('url'),
          'user_id' => $uid,
          'hits' => 0,
          'isPrivate' => $isPrivate
        ));

        //$response['success'] = true;
        $response['message'] = "Paste Saved!";
        Session::flash('home', 'Paste has been Saved!');
        $redirectURL = Config::get('appinfo/baseURL').'view/'.Form::data('url');
        Redirect::to($redirectURL);

      } catch(Exception $e){
        die($e->getMessage());
      }
      


    } else {
      $response['success'] = false;
      $x = 1;
      foreach ($validation->errors() as $error) {
        $response['message'] = $error;
        $x = $x + 1;
      }
    }

    //} //if end of Token check
}

?>

<html>
  <head>
    <title><?php echo Config::get('appinfo/title'); ?></title>
    <!-- Material Design Lite -->
    <script src="js/material.min.js"></script>
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!-- Folder effect -->
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script>
      document.documentElement.className = 'js';
      function browserCanUseCssVariables() { return window.CSS && window.CSS.supports && window.CSS.supports('--fake-var', 0); }
      if (!browserCanUseCssVariables()) alert('Your browser does not support CSS Variables. Please use a modern browser to view this demo.');
    </script>
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
      <main class="mdl-layout__content">
        <div class="page-content">

<?php if($user->isLoggedIn()){ ?>
               <div class="mdl-cell mdl-cell--12-col">
<?php } else { ?>
                <div class="mdl-cell mdl-cell--12-col">
<?php } ?>
              <h4>
                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect">
                  <i class="material-icons">format_align_right</i>
                </button> New Paste
              </h4>
              <div class="demo-card-wide mdl-card mdl-shadow--2dp" style="width: 100%;">
                <div class="mdl-card__supporting-text">
                  <form action="" method="POST">
                    <span class="mdl-chip mdl-chip--contact">
                        <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">i</span>
                        <span class="mdl-chip__text"><?php foreach ($response as $info) {
                            echo $info;
                        } ?></span>
                    </span>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%">
                      <input class="mdl-textfield__input" type="text" name="title" id="title" value="<?php echo Form::data('title'); ?>">
                      <label class="mdl-textfield__label" for="title">Paste Title</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label" style="width: 100%;">
                      <textarea class="mdl-textfield__input" type="text" rows= "10" name="content" id="content" style="background-color: #ebebeb;"><?php echo Form::data('content'); ?></textarea>
                      <label class="mdl-textfield__label" for="content">Text lines...</label>
                    </div>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                      <?php require 'inc/templates/syntax.php'; ?>
                      <label class="mdl-textfield__label" for="syntax">Syntax</label>
                    </div><br/>
<?php if($user->isLoggedIn()) { ?>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                      <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-1">
                        <input type="checkbox" id="switch-1" class="mdl-switch__input" name="isPrivate">
                        <span class="mdl-switch__label">Private</span>
                      </label>
                    </div><br/><br/>
<?php }else{ ?>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                      <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="switch-1">
                        <input type="checkbox" id="switch-1" class="mdl-switch__input" name="isPrivate" disabled>
                        <span class="mdl-switch__label">Private (Members only)</span>
                      </label>
                    </div><br/><br/>
<?php } ?>

<?php if($user->isLoggedIn()){ ?>

                    <span><strong>URL:</strong>(Change Optional)</span><br/>
                    <span style="color: #279e4d"><b><?php echo Config::get('appinfo/baseURL'); ?>view/</b></span>
                    <div class="mdl-textfield mdl-js-textfield">
                      <input class="mdl-textfield__input" style="color: #355dd6" type="text" name="url" id="url" value="<?php echo Hash::randomString(5); ?>">
                      <label class="mdl-textfield__label" for="url"></label>
                    </div><br/>
<?php }else{ ?>
                    <span><strong>Custom URL:</strong>(Members only)</span><br/>
                    <span style="color: #279e4d"><b><?php echo Config::get('appinfo/baseURL'); ?>view/</b></span>
                    <div class="mdl-textfield mdl-js-textfield">
                      <input class="mdl-textfield__input" style="color: #f45642" type="text" name="url" id="url" value="<?php echo Hash::randomString(5); ?>" disabled>
                      <label class="mdl-textfield__label" for="url"></label>
                    </div><br/>
                    <input type="hidden" name="url" id="url" value="<?php echo Hash::randomString(5); ?>" >
<?php } ?>



                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                      Paste
                    </button>
                    <span class="mdl-layout-spacer"></span>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                      Cancel
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>

<?php if($user->isLoggedIn()){ ?>
          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
              <h4>
                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect">
                  <i class="material-icons">keyboard</i>
                </button> <a href="<?php Config::get('appinfo/baseURL'); ?>my_paste">My Paste+</a>
              </h4>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" width="100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th class="mdl-data-table__cell--non-numeric">Title</th>
                    <th>Privacy</th>
                    <th>Creation Time</th>
                    <th>Syntax</th>
                    <th>Hits</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $get_paste = DB::operation()->query("SELECT * FROM paste_data WHERE content_type = 1 AND user_id = ? LIMIT 40", array($user->data()->name));
                  if($get_paste->count()){
                    foreach ($get_paste->results() as $got_user_paste) {
                      echo "<tr>";
                      echo "<td>".$got_user_paste->id."</td>";
                      echo '<td class="mdl-data-table__cell--non-numeric"><a href="';
                      echo Config::get('appinfo/baseURL');
                      echo 'view/'.$got_user_paste->url;
                      echo '">'.substr($got_user_paste->title, 0, 20);
                      if (strlen($got_user_paste->title) > 20) {
                        echo ' ....';
                      }
                      echo '</a></td>';
                      if ($got_user_paste->isPrivate == 1) {
                        echo '<td><i class="material-icons" style="color: #2abfa8">fingerprint</i></td>';
                      }else{
                        echo '<td><i class="material-icons">language</i></td>';
                      }
                      /*echo "<td>".$got_user_paste->isPrivate."</td>";*/
                      echo "<td>".$got_user_paste->creation_time."</td>";
                      echo "<td>".$got_user_paste->syntax."</td>";
                      echo "<td>".$got_user_paste->hits."</td>";
                      echo "</tr>";
                    }
                  }
                  ?>
                </tbody>
              </table><br/>
              <a href="<?php Config::get('appinfo/baseURL'); ?>my_paste" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent" style="background-color: #38b793; color: #fff">All of my Paste</a>
            </div>
          </div>
<?php } ?>
          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--7-col mdl-typography--text-center">
              <h4>
                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect">
                  <i class="material-icons">keyboard</i>
                </button> Latest Paste
              </h4>
              <center>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                  <tr>
                    <th class="mdl-data-table__cell--non-numeric">Title</th>
                    <th>Pasted</th>
                    <th>Author</th>
                    <th>Hits</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $get_hot_paste = DB::operation()->query('SELECT * FROM paste_data WHERE content_type = 1 AND isPrivate = 0 ORDER BY creation_time DESC LIMIT 40');
                  if ($get_hot_paste->count()) {
                    foreach ($get_hot_paste->results() as $hot_paste) {
                      echo '<tr>';
                      echo '<td class="mdl-data-table__cell--non-numeric"><a href="';
                      echo Config::get('appinfo/baseURL');
                      echo 'view/'.$hot_paste->url;
                      echo '">'.substr($hot_paste->title, 0, 20);
                      if (strlen($hot_paste->title) > 20) {
                        echo ' ....';
                      }
                      echo '</a></td>';

                      $datetime = new DateTime($hot_paste->creation_time);
                      $now = $now = new DateTime();
                      echo '<td><small>'.$datetime->diff($now)->format("%d days, %h hours and %i minuts ago").'</small></td>';

                      if ($hot_paste->user_id == '0') {
                        echo '<td><i class="material-icons">help</i></td>';
                      }else{
                        echo '<td>'.$hot_paste->user_id.'</td>';
                      }
                      echo '<td>'.$hot_paste->hits.'</td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </tbody>
              </table>
            </center>
            </div>
            <div class="mdl-cell mdl-cell--5-col mdl-typography--text-center">
              <h4>
                <button class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect">
                  <i class="material-icons">whatshot</i>
                </button> Public Hot Paste
              </h4>
              <center>
              <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                  <tr>
                    <th class="mdl-data-table__cell--non-numeric">Title</th>
                    <th>Author</th>
                    <th>Hits</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $get_hot_paste = DB::operation()->query('SELECT * FROM paste_data WHERE content_type = 1 AND isPrivate = 0 ORDER BY hits DESC LIMIT 40');
                  if ($get_hot_paste->count()) {
                    foreach ($get_hot_paste->results() as $hot_paste) {
                      echo '<tr>';
                      echo '<td class="mdl-data-table__cell--non-numeric"><a href="';
                      echo Config::get('appinfo/baseURL');
                      echo 'view/'.$hot_paste->url;
                      echo '">'.substr($hot_paste->title, 0, 20);
                      if (strlen($hot_paste->title) > 20) {
                        echo ' ....';
                      }
                      echo '</a></td>';



                      if ($hot_paste->user_id == '0') {
                        echo '<td><i class="material-icons">help</i></td>';
                      }else{
                        echo '<td>'.$hot_paste->user_id.'</td>';
                      }
                      echo '<td>'.$hot_paste->hits.'</td>';
                      echo '</tr>';
                    }
                  }
                  ?>
                </tbody>
              </table>
              </center>
            </div>
          </div>


        </div>
      </main>
    </div>

  </body>
</html>
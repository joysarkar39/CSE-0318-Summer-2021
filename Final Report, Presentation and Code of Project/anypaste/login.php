<?php
require_once 'corex/autoload.php';

$response = array();

if (Form::exists()) {
  if (Token::check(Form::data('token'))) {
    
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'username' => array('required' => true),
      'password' => array('required' => true)
    ));

    if($validation->passed()){

      $user = new User();

      $remember = (Form::data('remember') === 'on') ? true : false; 
      $login = $user->login(Form::data('username'), Form::data('password'), $remember);

      if ($login) {
        //$response["success"] = true;
        $response["message"] = "Login Successful!";
        Redirect::to('index.php');

        //Getting userinfo
          $username = Form::data('username');
          $user = new User($username);
          if (!$user->exists()) {
            Redirect::to(404);
          } else {
            $data = $user->data();
          }
          /*$response["email"] = $data->username;
          $response["name"] = $data->name;*/

      } else {
        $response["success"] = false;
        $response["message"] = "Invalid Username or Password";
      }

    } else {
      foreach ($validation->errors() as $error) {
        $response["message"] = $error;
      }
    }
  }
  //echo json_encode($response);
}
?>


<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Login</title>
  
      <link rel="stylesheet" href="css/style.css">

    <!-- Material Design Lite -->
    <script src="js/material.min.js"></script>
    <link rel="stylesheet" href="css/material.indigo-pink.min.css">
    <!-- Material Design icon font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style type="text/css">
    body {
      width: initial;
      color: rgba(0,0,0,.54);
    }
    </style>
</head>
<body>

  <div class="mdl-grid">
    <div class="mdl-layout-spacer"></div>
    <!-- Simple Textfield -->
    <div class="mdl-cell mdl-cell--4-col">
      <form action="" method="post">
        <h2>Login</h2>
        <!-- Contact Chip -->
        <span class="mdl-chip mdl-chip--contact">
            <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">i</span>
            <span class="mdl-chip__text"><?php foreach ($response as $info) {
              echo $info;
            } ?></span>
        </span><br/>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="username" type="text" id="sample1">
            <label class="mdl-textfield__label" for="sample1">Username</label>
          </div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password" type="password" id="sample1">
            <label class="mdl-textfield__label" for="sample1">Password</label>
          </div>

<!--
    <div class="field">
      <label for="remember" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
      <input type="checkbox" name="remember" id="remember" class="mdl-switch__input">
      <span class="mdl-switch__label">Remember Me</span>
      </label>
    </div>
          -->

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">



          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--6-col">
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary" style="background-color: #20b7a4;">
              Login
              </button>
            </div>
            <div class="mdl-cell mdl-cell--6-col">
              <a href="register.php" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">Register</a>
            </div>
          </div>


          
      </form>
    </div>
    <div class="mdl-layout-spacer"></div>
  </div>



  </body>
</html>


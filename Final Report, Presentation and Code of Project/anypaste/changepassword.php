<?php
require_once 'corex/autoload.php';
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
  
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

<?php
$response = array();
$user = new User();
if($user->isLoggedIn()) {



if (Form::exists()) {
  if (Token::check(Form::data('token'))) {
    
    $validate = new Validate();
    $validation = $validate->check($_POST, array(
      'password_current' => array(
        'required' => true,
        'min' => 6
      ),
      'password_new' => array(
        'required' => true,
        'min' => 6
      ),
      'password_new_again' => array(
        'required' => true,
        'min' => 6,
        'matches' => 'password_new'
      )
    ));

    if ($validation->passed()) {
      
      if (Hash::generate(Form::data('password_current'), $user->data()->salt) !== $user->data()->password) {
        $response['message'] = "Please Enter correct currrent password";
      } else {
        $salt = Hash::salt(32);
        $user->update(array(
          'password' => Hash::generate(Form::data('password_new'), $salt),
          'salt' => $salt
        ));

        Session::flash('home', 'Your Password has been changed!');
        Redirect::to('index.php');

      }

    } else {
      $x = 1;
      foreach ($validation->errors() as $error) {
        $response[$x] = $error;
        $x = $x+1;
      }
    }
  }
}

?>
  <div class="mdl-grid">
    <div class="mdl-layout-spacer"></div>
    <!-- Simple Textfield -->
    <div class="mdl-cell mdl-cell--4-col">
      <form action="" method="POST">
        <h3>Change Passsword</h3>
        <!-- Contact Chip -->
        <span class="mdl-chip mdl-chip--contact">
            <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">i</span>
            <span class="mdl-chip__text"><?php
            echo reset($response); ?></span>
        </span><br/>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password_current" type="password" id="password_current">
            <label class="mdl-textfield__label" for="password_current">Current Password</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password_new" type="password" id="password_new">
            <label class="mdl-textfield__label" for="password_new">New Password</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password_new_again" type="password" id="password_new_again">
            <label class="mdl-textfield__label" for="password_new_again">New Password Again</label>
          </div>

          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--6-col">
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary">
              Change Password
              </button>
            </div>
            <div class="mdl-cell mdl-cell--6-col">
              <a href="index.php" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">Go Back</a>
            </div>
          </div>


          
      </form>
    </div>
    <div class="mdl-layout-spacer"></div>
  </div>


<?php
} //login protected content end
else{
  Redirect::to('index.php');
}
?>
  </body>
</html>


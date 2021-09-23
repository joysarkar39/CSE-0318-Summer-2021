<?php
require_once 'corex/autoload.php';
$response = array();
if(Form::exists()) {
	// if (Token::check(Form::data('token'))) {

		

		$form = new Validate();
		$validation = $form->check($_POST, array(
				'username' => array(
						'required' => true,
						'min' => 2,
						'max' => 200,
						'unique' => 'users' //here 'users' is  the  table name. and field name is same to the HTML form's field
					),
				'password' => array(
						'required' => true,
						'min' => 6
					),
				'password_again' => array(
						'required' => true,
						'matches' => 'password'
					),
				'name' => array(
						'required' => true,
						'min' => 2,
						'max' => 50
					),
				'phone' => array(
						'required' => true,
						'min' => 10,
					),
				'birthday' => array(
						'required' => true
					),
				'country' => array(
						'required' => true
					),
				'city' => array(
						'required' => true
					),
				'sex' => array(
						'required' => true
					)
			));

		if ($validation->passed()) {
			$user = new User();

			$salt = Hash::salt(32);
			/*echo Hash::generate(Form::data('password'), $salt);*/


			try{
				$user->create(array(
					'username' => Form::data('username'),
					'password' => Hash::generate(Form::data('password'), $salt),
					'salt'     => $salt,
					'name'     => Form::data('name'),
					'joined'   => date('Y-m-d H:i:s'),
					'usrGroup' => 3,
					'phone' => Form::data('phone'),
					'birthday' => Form::data('birthday'),
					'country' => Form::data('country'),
					'city' => Form::data('city'),
					'sex' => Form::data('sex'),
          'avater' => 'files/avaters/avater.jpg'
				));

				//$response['success'] = true;
				$response['message'] = "Registration Successful! You can Login now!";

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

/*echo json_encode($response);*/
?>

<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  
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
        <h3>Register</h3>
        <!-- Contact Chip -->
        <span class="mdl-chip mdl-chip--contact">
            <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white">i</span>
            <span class="mdl-chip__text"><?php foreach ($response as $info) {
            		echo $info;
            } ?></span>
        </span><br/>



          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="username" type="text" id="username" value="<?php echo Form::data('username'); ?>">
            <label class="mdl-textfield__label" for="username">Username</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password" type="password" id="password" value="">
            <label class="mdl-textfield__label" for="password">Password</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="password_again" type="password" id="password_again" value="">
            <label class="mdl-textfield__label" for="password_again">Password Again</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="name" type="text" id="name" value="<?php echo Form::data('name'); ?>">
            <label class="mdl-textfield__label" for="name">Name</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="phone" type="text" id="phone" pattern="-?[0-9]*(\.[0-9]+)?" value="<?php echo Form::data('phone'); ?>">
            <label class="mdl-textfield__label" for="phone">Phone</label>
            <span class="mdl-textfield__error">Input is not a number!</span>
          </div>


          <div class="mdl-textfield mdl-js-textfield">
            <?php require 'inc/templates/country_list.php'; ?>
            <label class="mdl-textfield__label" for="country">Country</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="city" type="text" id="city" value="<?php echo Form::data('city'); ?>">
            <label class="mdl-textfield__label" for="city">City</label>
          </div>

          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" name="birthday" type="text" id="birthday" value="<?php echo Form::data('birthday'); ?>">
            <label class="mdl-textfield__label" for="birthday">Birthday (YYYY-MM-DD):</label>
          </div>

<br/>
<label class="mdl-radiolabel__label" for="sex">Sex:</label>
<br/>
	<label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-1">
      <input type="radio" id="option-1" class="mdl-radio__button" name="sex" value="male" checked>
      <span class="mdl-radio__label">Male</span>
    </label>
    <br/>
    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-2">
      <input type="radio" id="option-2" class="mdl-radio__button" name="sex" value="female">
      <span class="mdl-radio__label">Female</span>
    </label>
    <br/>
    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="option-3">
      <input type="radio" id="option-3" class="mdl-radio__button" name="sex" value="other">
      <span class="mdl-radio__label">Other</span>
    </label>




          <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">



          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--6-col">
              <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary">
              Register
              </button>
            </div>
            <div class="mdl-cell mdl-cell--6-col">
              <a href="login.php" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">Login</a>
            </div>
          </div>


          
      </form>
    </div>
    <div class="mdl-layout-spacer"></div>
  </div>



  </body>
</html>


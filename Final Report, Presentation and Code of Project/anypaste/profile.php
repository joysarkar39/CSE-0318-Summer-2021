<?php
require_once 'corex/autoload.php';

if(!$username = Form::data('user')) {
  Redirect::to('index.php');
}else{
  $user = new User($username);
  if(!$user->exists()){
    Redirect::to(404);
  } else{
    $data = $user->data();
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title><?php echo $data->name; ?></title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">


    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.deep_purple-pink.min.css">
    <link rel="stylesheet" href="css/profile.css">
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }


    .image-cropper {
        width: 100px;
        height: 100px;
        position: relative;
        overflow: hidden;
        border-radius: 50%;
    }

    img {
        display: inline;
        margin: 0 auto;
        height: 100%;
        width: auto;
    }
    </style>
  </head>
  <body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
      <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--primary">
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
          <h3><?php echo $data->name; ?></h3>
        </div>
        <div class="mdl-layout--large-screen-only mdl-layout__header-row">
        </div>
        <div class="mdl-layout__tab-bar mdl-js-ripple-effect mdl-color--primary-dark">
          <a href="#features" class="mdl-layout__tab is-active">Profile</a>
          <a href="index.php"><button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored mdl-shadow--4dp mdl-color--accent" id="add">
            <i class="material-icons" role="presentation">home</i>
            <span class="visuallyhidden">Add</span>
          </button></a>
        </div>
      </header>
      <main class="mdl-layout__content">
        <div class="mdl-layout__tab-panel is-active" id="features">
          <section class="section--center mdl-grid mdl-grid--no-spacing">
            <div class="mdl-cell mdl-cell--4-col">

              <div class="image-cropper">
                  <img src="<?php echo $user->data()->avater; ?>" class="rounded" />
              </div>

            </div>
            <div class="mdl-cell mdl-cell--8-col">
              <h2><?php echo $data->name; ?></h2>


                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" width="100%">
                  <tbody>
                    <tr>
                      <td class="mdl-data-table__cell--non-numeric">Location</td>
                      <td class="mdl-data-table__cell--non-numeric"><?php echo $data->city; ?>, <?php echo $data->country; ?></td>
                    </tr>
                    <tr>
                      <td class="mdl-data-table__cell--non-numeric">Sex</td>
                      <td class="mdl-data-table__cell--non-numeric"><?php echo $data->sex; ?></td>
                    </tr>
                    <tr>
                      <td class="mdl-data-table__cell--non-numeric">Birthday</td>
                      <td class="mdl-data-table__cell--non-numeric"><?php echo $data->birthday; ?></td>
                    </tr>
                    <tr>
                      <td class="mdl-data-table__cell--non-numeric">Phone</td>
                      <td class="mdl-data-table__cell--non-numeric"><?php echo $data->phone; ?></td>
                    </tr>
                  </tbody>
                </table>


            </div>
          </section>
        </div>
        
      </main>
    </div>
    
    <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>

<?php
}
?>
  </body>
</html>

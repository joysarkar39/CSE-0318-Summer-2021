<?php
require_once 'corex/autoload.php';

$user = new User();
$user->logout();
echo "hello";
Redirect::to('index.php');
Session::flash('home', 'Logged Out!');

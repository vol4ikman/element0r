<?php
session_start();

include 'functions.php';

$user_email = ( isset( $_POST['user_email'] ) && $_POST['user_email'] ) ? $_POST['user_email'] : '';
$user_pass  = ( isset( $_POST['user_pass'] ) && $_POST['user_pass'] ) ? $_POST['user_pass']    : '';

$login_user = login_user( $user_email, $user_pass );

if( ! $login_user['error'] && $login_user['user_id'] ){
    $_SESSION['user_id']    = $login_user['user_id'];
    $_SESSION['user_email'] = $login_user['user_email'];
}

header("Location: http://volkov.co.il/elementor/");
exit;

<?php
    session_start();
    include 'functions.php';
    $logout_user = logout_user( get_current_user_email() );
    session_destroy();
    header("Location: http://volkov.co.il/elementor/");
    exit;
?>

<?php
if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'] ) ) {
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
    die( 'Access denied' );
}

include 'functions.php';

$action = ( isset( $_POST['action'] ) && $_POST['action'] ) ? $_POST['action'] : '';
if( $action == 'loadUserData' ){

    $user_id    = ( isset( $_POST['user_id'] ) && $_POST['user_id'] ) ? $_POST['user_id'] : '';
    $popup_html = '';
    $user       = get_user_by_id($user_id);

    if( $user ){
        ob_start();
        ?>

        <div id="user-popup">

            <div class="user-popup-inner">

                <button type="button" class="close-popup" onclick="closePopup()">[x]</button>

                <div class="user-popup-header">
                    <h3>User details</h3>
                </div>

                <div class="user-popup-body">
                    <ol>
                        <li>ID: <?php echo isset($user[0]) ? $user[0] : ''; ?></li>
                        <li>Email: <?php echo isset($user[1]) ? $user[1] : ''; ?></li>
                        <li>Registration date: <?php echo isset($user[3]) ? $user[3] : ''; ?></li>
                        <li>Status: <?php echo isset($user[4]) ? $user[4] : ''; ?></li>
                        <li>IP address: <?php echo isset($user[5]) ? $user[5] : ''; ?></li>
                    </ol>
                </div>

            </div>

        </div>

        <?php
        $popup_html = ob_get_clean();
    }

    $response = array(
        'user_id'    => $user_id,
        'popup_html' => $popup_html
    );

    echo json_encode($response);
    die();
}

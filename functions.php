<?php
function login_user( $user_email, $user_pass ){

    $response = array(
        'error'      => true,
        'message'    => '',
        'user_id'    => '',
        'user_email' => ''
    );
    $password_is_valid = false;
    $file              = 'users-db.txt';
    $searchfor         = $user_email;

    header('Content-Type: text/plain');

    $contents = file_get_contents($file);
    $pattern  = preg_quote($searchfor, '/');
    $pattern  = "/^.*$pattern.*\$/m";

    if( preg_match_all( $pattern, $contents, $matches ) ){
        $result = implode("\n", $matches[0]);
        $tmp_results = $result;

        if( $result ){
            /*
                [0] => 1                                    // user id
                [1] => alex@gmail.com                       // user email
                [2] => c8837b23ff8aaa8a2dde915473ce0991     // user pass md5
                [3] => 01/08/2020                           // reg date
                [4] => offline                              // online status
                [5] => ip
            */
            $result = explode("|",$result);

            if( $result[2] === md5($user_pass) ){

                $password_is_valid      = true;
                $response['error']      = false;
                $response['message']    = 'Welcome, ' . $result[1];
                $response['user_id']    = $result[0];
                $response['user_email'] = $result[1];

                $data = file($file, FILE_SKIP_EMPTY_LINES); // reads an array of lines
                $row = '';
                foreach( $data as $index=>$value ){
                    $value          = trim(preg_replace('/\s\s+/', ' ', $value));
                    $string_result  = trim(preg_replace('/\s\s+/', ' ', $tmp_results));
                    if( $value == $string_result ){
                        $row = $index;
                        break;
                    }
                }

                $updated_txt_file = array();

                foreach( $data as $line_key=>$line_value ){

                    if( is_numeric($row) ){
                        if( $line_key == $row ){
                            // '3|alex3@gmail.com|c8837b23ff8aaa8a2dde915473ce0991|01/08/2020|offline|0.000.000.000'
                            $updated_txt_file[] = trim(preg_replace('/\s\s+/', ' ', $result[0].'|'.$result[1].'|'.$result[2].'|'.$result[3].'|online|'.get_user_ip()));
                        } else {
                            $updated_txt_file[] = trim(preg_replace('/\s\s+/', ' ', $line_value));
                        }
                    }
                }

                if( $updated_txt_file ){
                    update_db_file( $file, $updated_txt_file );
                }

            } else {
               $response['message'] = "Wrong password. Please try again.";
            }
        }
    } else {
        $response['message'] = "User doesn't exists";
    }

    return $response;
}

function user_logged_in(){
    if( isset( $_SESSION['user_id'] ) && $_SESSION['user_id'] ){
        return true;
    }
    return false;
}

function get_all_users(){
    $file  = 'users-db.txt';
    $data  = file($file);
    $users = array();
    if( $data ){
        foreach( $data as $index=>$item ){
            if( isset( $item[1] ) && isset( $item[2] ) ){
                $users[] = explode("|",$item);
            }
        }
    }

    return $users;
}

function get_current_user_id(){
    if( isset( $_SESSION['user_id'] ) && $_SESSION['user_id'] ){
        return $_SESSION['user_id'];
    }
    return false;
}

function get_current_user_email(){
    if( isset( $_SESSION['user_email'] ) && $_SESSION['user_email'] ){
        return $_SESSION['user_email'];
    }
    return false;
}

function logout_user( $user_email ) {

    $file      = 'users-db.txt';
    $searchfor = $user_email;

    header('Content-Type: text/plain');

    $contents = file_get_contents($file);
    $pattern  = preg_quote($searchfor, '/');
    $pattern  = "/^.*$pattern.*\$/m";

    if( preg_match_all( $pattern, $contents, $matches ) ){
        $result = implode("\n", $matches[0]);
        $tmp_results = $result;

        if( $result ){

            $result = explode("|",$result);
            $data   = file($file, FILE_SKIP_EMPTY_LINES); // reads an array of lines
            $row    = '';

            foreach( $data as $index=>$value ){
                $value          = trim(preg_replace('/\s\s+/', ' ', $value));
                $string_result  = trim(preg_replace('/\s\s+/', ' ', $tmp_results));
                if( $value == $string_result ){
                    $row = $index;
                    break;
                }
            }

            $updated_txt_file = array();

            foreach( $data as $line_key=>$line_value ){

                if( is_numeric($row) ){
                    if( $line_key == $row ){
                        $updated_txt_file[] = trim(preg_replace('/\s\s+/', ' ', $result[0].'|'.$result[1].'|'.$result[2].'|'.$result[3].'|offline|'.get_user_ip()));
                    } else {
                        $updated_txt_file[] = trim(preg_replace('/\s\s+/', ' ', $line_value));
                    }
                }
            }

            if( $updated_txt_file ){
                update_db_file( $file, $updated_txt_file );
                return true;
            }


        }
    }

    return false;
}

function get_user_by_id( $user_id ){

    $file      = 'users-db.txt';
    $searchfor = $user_id;
    $user      = array();

    header('Content-Type: text/plain');

    $contents = file_get_contents($file);
    $pattern  = preg_quote($searchfor, '/');
    $pattern  = "/^.*$pattern.*\$/m";

    if( preg_match_all( $pattern, $contents, $matches ) ){

        $result = $matches[0];
        $row    = '';
        if( $result ){

            foreach ( $result as $index=>$value ){
                $row_data = explode('|', $value );
                if( isset( $row_data[0] ) && ( $row_data[0] == $user_id ) ){
                    $row = $index;
                }
            }

            $data = file($file, FILE_SKIP_EMPTY_LINES); // reads an array of lines
            if( is_numeric($row) && $data[$row] ){
                $user = explode( '|', $data[$row] );
                if( $user ){
                    return $user;
                }
            }

        }
    }

    return false;
}

function update_db_file( $file, $updated_txt_file ){
    $file_content_update = implode( "\n", str_replace( ' ', '', $updated_txt_file ) );
    file_put_contents( $file, $file_content_update );
}

function get_user_ip(){
    if( !empty($_SERVER['HTTP_CLIENT_IP']) ){
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

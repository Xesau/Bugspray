<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) &&
   !empty( $_POST[ 'password' ] ) &&
   !empty( $_POST[ 'repeat_password' ] ) &&
   !empty( $_POST[ 'fullname' ] ) )
{
    if( $_POST[ 'repeat_password' ] == $_POST[ 'password' ] )
    {
        if( filter_var( $_POST[ 'email' ], FILTER_VALIDATE_EMAIL ) )
        {
            $salt = substr( md5( rand(0,9999999) ), 0, 22 );

            DB::table( prefix( 'users' ) )->insert( [
                'email' => $_POST[ 'email' ],
                'displayname' => $_POST[ 'fullname' ],
                'password' => password_hash( $_POST[ 'password' ], PASSWORD_BCRYPT, [ 'salt' => $salt ] ),
                'salt' => $salt,
                'activated' => '0',
                'banned' => '0',
                'ban_reason' => '',
                'ban_expire' => '',
                'last_login' => '-1',
                'registered' => time(),
                'last_ip' => '',
                'register_ip' => $_SERVER[ 'REMOTE_ADDR' ]
            ] );
            
            $activation_code = rand( 0, 99999 );
            DB::table( prefix( 'activation_keys' ) )->insert( [
                'id' => DB::mysqli()->insert_id,
                'activation_code' => $activation_code
            ] );
            
            $sn = setting( 'site_name' );
            $bn = setting( 'base_url' );
            $message = str_replace( '%sitename%', $sn, $l[ 'registration_mail_text' ] );
            $message = str_replace( '%activation_link%', $bn . '/activate/' . $activation_code, $message );
            $message = str_replace( '%activation_page%', $bn . '/activate', $message );
            $message = str_replace( '%name%', $_POST[ 'fullname' ], $message );
            
            $headers  = 'From: ' . $sn . ' <' . setting( 'admin_email' ) . '>' . "\r\n";
            $headers .= 'Content-Type: text/html; charset=utf-16';
            
            mail( $_POST[ 'email' ], str_replace('%sitename%', setting( 'site_name' ), $l[ 'registration_mail_title' ] ), $message, $headers );
            
            header( 'Location: ' . setting( 'base_url' ) . '/activate' );
        }
        else
        {
            ## TODO: ERROR EMAIL NOT EMAIL
            $_SESSION[ 'register_error' ] = 'email_syntax';
            $_SESSION[ 'register_fields' ] = $_POST;
            header( 'Location: ' . setting( 'base_url' ) . '/login' );
        }
    }
    else
    {
        ## TODO: ERROR PASSWORD NOT CORRECT
        $_SESSION[ 'register_error' ] = 'passwords_dont_match';
        $_SESSION[ 'register_fields' ] = $_POST;
        header( 'Location: ' . setting( 'base_url' ) . '/login' );
    }
}
else
{
    $_SESSION[ 'register_error' ] = 'fields';
    $_SESSION[ 'register_fields' ] = $_POST;
    header( 'Location: ' . setting( 'base_url' ) . '/login' );
}

?>
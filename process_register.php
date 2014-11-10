<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) &&
   !empty( $_POST[ 'password' ] ) &&
   !empty( $_POST[ 'repeat_password' ] ) &&
   !empty( $_POST[ 'fullname' ] ) )
{
    ## CREATE USER ACCOUNT activated=0
    
    
    header( 'Location: ' . setting( 'base_url' ) . '/register_activate' );
}
else
{
    $_SESSION[ 'register_error' ] = 'fields';
    $_SESSION[ 'register_fields' ] = $_POST;
    header( 'Location: ' . setting( 'base_url' ) . '/login' );
}

?>
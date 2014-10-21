<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( isset( $_POST[ 'email' ] ) && isset( $_GET[ 'password' ] ) && isset( $_GET[ 'password' ] ) && isset( $_GET[ 'password' ] ) )
{   
    
}
else
{   $_SESSION[ 'register_error' ] = 'fields';
    header( 'Location: ' . setting( 'base_url' ) . '/login' );
}

?>
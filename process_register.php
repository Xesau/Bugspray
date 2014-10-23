<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) && !empty( $_GET[ 'password' ] ) && !empty( $_GET[ 'password' ] ) && !empty( $_GET[ 'password' ] ) )
{   
    
}
else
{   $_SESSION[ 'register_error' ] = 'fields';
    header( 'Location: ' . setting( 'base_url' ) . '/login' );
}

?>
<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'NO_TPL', true );
define( 'NO_PLUGINS', true );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) && !empty( $_POST[ 'password' ] ) )
{
    unset( $_SESSION[ 'admin_user_id' ] );
	unset( $_SESSION[ 'login_error' ] );
	unset( $_SESSION[ 'login_email' ] );

    $table = DB::table( prefix( 'users' ) );
    $select = $table->select( 'id,password,salt', array( 'where' => 'email = \'' . DB::escape( $_POST[ 'email' ] ) . '\''  ) );
    if( $select->size() > 0 )
    {
        $fields = $select->getEntry( 0 )->getFields();
        if( crypt( $_POST[ 'password' ], '$2y$10$' . $fields[ 'salt' ] . '$' ) === $fields[ 'password' ] )
        {
            if( hasPermission( $fields[ 'id' ], 'bs_admin' ) )
            {
                $table->update(
                    array( 'last_login' => time(), 'last_ip' => $_SERVER[ 'REMOTE_ADDR' ] ),
                    array( 'where' => 'id = ' . $select->getEntry( 0 )->getField( 'id' ) )
                );
                $_SESSION[ 'admin_user_id' ] = $select->getEntry( 0 )->getField( 'id' );
                header( 'Location: ' . setting( 'base_url' ) . '/admin/home' );
            }
            else
            {
                $_SESSION[ 'login_error' ] = 'no_permission';
                $_SESSION[ 'login_email' ] = $_POST[ 'email' ];
                header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
            }
        }
        else
        {
            $_SESSION[ 'login_error' ] = 'incorrect_password';
            $_SESSION[ 'login_email' ] = $_POST[ 'email' ];
            header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
        }
    }
    else
    {
        $_SESSION[ 'login_error' ] = 'incorrect_email';
        header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
    }
}
else
{
    $_SESSION[ 'login_error' ] = 'data_missing';
	header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
}

<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) )
{
    unset( $_SESSION[ 'admin_user_id' ] );
	unset( $_SESSION[ 'login_error' ] );
	unset( $_SESSION[ 'login_email' ] );
	
    if( !empty( $_POST[ 'password' ] ) )
	{
        $table = DB::table( prefix( 'users' ) );
		$select = $table->select( 'id,password,salt', array( 'where' => 'email = \'' . DB::escape( $_POST[ 'email' ] ) . '\''  ) );
		if( $select->size() > 0 )
		{
            $fields = $select->getEntry( 0 )->getFields();
			if( password_hash( $_POST[ 'password' ], PASSWORD_BCRYPT, [ 'salt' => $fields[ 'salt' ] ] ) === $fields[ 'password' ] )
			{
                if( hasPermission( $fields[ 'id' ], 'bs_admin' ) )
                {
                    $table->update(
                        array( 'last_login' => time(), 'last_ip' => $_SERVER[ 'HTTP_REFERER' ] ),
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
            exit('i');
			header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
		}
	}
}
else
{
    $_SESSION[ 'login_error' ] = 'fields';
	header( 'Location: ' . setting( 'base_url' ) . '/admin/login' );
}

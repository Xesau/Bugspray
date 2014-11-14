<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );
define( 'NO_TPL', true );
define( 'NO_PLUGINS', true );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) )
{
	unset( $_SESSION[ 'user_id' ] );
	unset( $_SESSION[ 'login_error' ] );
	unset( $_SESSION[ 'login_email' ] );
	if( !empty( $_POST[ 'password' ] ) )
	{	$table = DB::table( prefix( 'users' ) );
		$select = $table->select( 'id,password,salt', array( 'where' => 'email = \'' . DB::escape( $_POST[ 'email' ] ) . '\''  ) );
		if( $select->size() > 0 )
		{	$fields = $select->getEntry( 0 )->getFields();
			if( password_hash( $_POST[ 'password' ], PASSWORD_BCRYPT, [ 'salt' => $fields[ 'salt' ] ] ) === $fields[ 'password' ] )
			{	$table->update(
					array( 'last_login' => time(), 'last_ip' => $_SERVER[ 'REMOTE_ADDR' ] ),
					array( 'where' => 'id = ' . $select->getEntry( 0 )->getField( 'id' ) )
				);
				$_SESSION[ 'user_id' ] = $select->getEntry( 0 )->getField( 'id' );
				header( 'Location: ' . setting( 'base_url' ) );
			}
			else
			{	$_SESSION[ 'login_error' ] = 'password';
				$_SESSION[ 'login_email' ] = $_POST[ 'email' ];
				header( 'Location: ' . setting( 'base_url' ) . '/login' );
			}
		}
		else
		{	$_SESSION[ 'login_error' ] = 'email';
			header( 'Location: ' . setting( 'base_url' ) . '/login' );
		}
	}
}
else
{	$_SESSION[ 'login_error' ] = 'fields';
	header( 'Location: ' . setting( 'base_url' ) . '/login' );
}

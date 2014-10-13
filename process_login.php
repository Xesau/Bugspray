<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

if( !empty( $_POST[ 'email' ] ) )
	if( !empty( $_POST[ 'password' ] ) )
	{
		$select = $db->table( prefix( 'users' ) )->select( 'id,password', array( 'where' => 'email = \'' . $db->escape( $_POST[ 'email' ] ) . '\''  ) );
		if( $select->size() > 0 )
		{
			if( crypt( $_POST[ 'password' ], CRYPT_BLOWFISH ) === $select->getEntry( 0 )->getField( 'password' ) )
			{
				$table &= $db->table( prefix( 'users' ) );
				$table->update(
					array( 'last_login' => time(), 'last_ip' => $_SERVER[ 'HTTP_REFERER' ] ),
					array( 'where' => 'id = ' . $select->getEntry( 0 )->getField( 'id' ) )
				);
				$_SESSION[ 'user_id' ] = $select->getEntry( 0 )->getField( 0 );
				header( 'Location: ./' );
			}
			$_SESSION[ 'login_error' ] = 'password';
			$_SESSION[ 'login_email' ] = $_POST[ 'email' ];
			header( 'Location: login' );
		}
		$_SESSION[ 'login_error' ] = 'email';
		header( 'Location: login' );
	}
$_SESSION[ 'login_error' ] = 'fields';
header( 'Location: login' );
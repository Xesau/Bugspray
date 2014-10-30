<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'IN_INSTALL_COMPLETION', true );
define( 'IN_INSTALL', true );

require_once CDIR . '/core/global.inc.php';
require_once CDIR . '/core/miqrodb.class.php';

### POST CHECK
if( empty( $_POST[ 'site_name' ] ) ||
   empty( $_POST[ 'admin_email' ] ) ||
   empty( $_POST[ 'base_url' ] ) ||
   empty( $_POST[ 'language' ] ) ||
   empty( $_POST[ 'db_host' ] ) ||
   empty( $_POST[ 'db_username' ] ) ||
   empty( $_POST[ 'db_password' ] ) ||
   empty( $_POST[ 'db_name' ] ) ||
   empty( $_POST[ 'admin_username' ] ) ||
   empty( $_POST[ 'admin_password' ] ) ||
   empty( $_POST[ 'admin_repeat_password' ] ) ||
   empty( $_POST[ 'admin_name' ] ) )
    exit( 'not_all_fields' );

### DATA CHECK
if( strlen( $_POST[ 'admin_password' ] ) < 8 ||
  $_POST[ 'admin_password' ] !== $_POST[ 'admin_repeat_password' ] )
    exit( 'data error' );
    
$mysqli = @new MySQLi( $_POST[ 'db_host' ], $_POST[ 'db_username' ], $_POST[ 'db_password' ], $_POST[ 'db_name' ] );
if( $mysqli->connect_error )
    exit( 'conn err' );

$db = new MiqroDB( $mysqli );

file_put_contents( CDIR . '/core/config.inc.php', '<?php
# Generated on ' . date( 'd-m-y h:i:s', time() ) . '

$db_host = \'' . $_POST[ 'db_host' ] . '\';
$db_user = \'' . $_POST[ 'db_username' ] . '\';
$db_password = \'' . $_POST[ 'db_password' ] . '\';
$db_database = \'' . $_POST[ 'db_name' ] . '\';

$db_prefix = \'' . ( isset( $_POST[ 'db_prefix' ] ) ? $_POST[ 'db_prefix' ] : '' ) . '\';
');

if( isset( $_POST[ 'db_prefix' ] ) )
{ function prefix( $tableName ) { global $_POST; return $_POST[ 'db_prefix' ] . $tableName; }; }
else
{ function prefix( $tableName ) { return $tableName; } }
   
$db->createTable( prefix( 'settings' ), [
    'setting' => [
        'type' => 'varchar',
        'length' => 100,
        'unique' => true
    ],
    'value' => [
        'type' => 'text',
        'length' => null
    ]
], [ 'ifNotExists' => true ] );

$db->createTable( prefix( 'users' ), [
    'id' => [
        'autoIncrement' => true,
        'primary' => true
    ],
    'email' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'displayname' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'password' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'salt' => [
        'type' => 'varchar',
        'length' => 20
    ],
    'banned' => [
        'type' => 'enum',
        'data' => "'0','1'",
        'default' => '0',
    ],
    'ban_reason' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'ban_expire' => [
        'type' => 'int',
        'length' => 11
    ],
    'last_login' => [],
    'registered' => [],
    'last_ip' => [
        'type' => 'varchar',
        'length' => 20
    ],
    'register_ip' => [
        'type' => 'varchar',
        'length' => 20
    ]
], [ 'ifNotExists' => true ] );
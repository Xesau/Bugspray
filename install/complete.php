<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'IN_INSTALL_COMPLETION', true );
define( 'IN_INSTALL', true );

require_once CDIR . '/core/global.inc.php';
require_once CDIR . '/core/miqrodb.class.php';

MiqroDB::$debug = true;

### POST CHECK
if( empty( $_POST[ 'site_name' ] ) ||
   empty( $_POST[ 'admin_email' ] ) ||
   empty( $_POST[ 'base_url' ] ) ||
   empty( $_POST[ 'language' ] ) ||
   empty( $_POST[ 'db_host' ] ) ||
   empty( $_POST[ 'db_username' ] ) ||
   empty( $_POST[ 'db_password' ] ) ||
   empty( $_POST[ 'db_name' ] ) ||
   empty( $_POST[ 'admin_password' ] ) ||
   empty( $_POST[ 'admin_repeat_password' ] ) ||
   empty( $_POST[ 'admin_name' ] ) )
{
    $template = 'error';
    $error = 'fields';
    goto draw_template;
}

### VALIDATE CHECK
if( $_POST[ 'admin_password' ] !== $_POST[ 'admin_repeat_password' ] )
{
    $template = 'error';
    $error = 'password_equality';
    goto draw_template;
}

if( strlen( $_POST[ 'admin_password' ] ) < 8 )
{
    $template = 'error';
    $error = 'password_legnth';
    goto draw_template;
}
    
$mysqli = @new MySQLi( $_POST[ 'db_host' ], $_POST[ 'db_username' ], $_POST[ 'db_password' ], $_POST[ 'db_name' ] );
if( $mysqli->connect_error )
{
    $template = 'error';
    $error = 'mysql';
    goto draw_template;
}

$db = new MiqroDB( $mysqli );

file_put_contents( CDIR . '/core/config.inc.php', '<?php

if( !defined( \'CDIR\' ) ) exit( \'Direct access not allowed\' );

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

### CREATE SETTINGS TABLE
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

### INSERT DEFAULT SETTINGS
$db->table( prefix( 'settings' ) )->insertMany( [
	[ 'setting' => 'site_name', 'value' => $_POST[ 'site_name' ] ],
	[ 'setting' => 'base_url', 'value' => $_POST[ 'base_url' ] ],
	[ 'setting' => 'debug_mode', 'value' => 'false' ],
	[ 'setting' => 'issue_labels', 'value' => 'true' ],
	[ 'setting' => 'issue_priority', 'value' => 'true' ],
	[ 'setting' => 'issue_project_version', 'value' => 'true' ],
	[ 'setting' => 'issue_security', 'value' => 'true' ],
	[ 'setting' => 'language', 'value' => $_POST[ 'language' ] ],
	[ 'setting' => 'theme', 'value' => $_POST[ 'theme' ] ],
	[ 'setting' => 'admin_email', 'value' => $_POST[ 'admin_email' ] ],
], [ 'existsKey' => 'setting' ] );


### CREATE USER TABLE
$db->createTable( prefix( 'users' ), [
    'id' => [
        'autoIncrement' => true,
        'primary' => true
    ],
    'email' => [
        'type' => 'varchar',
        'length' => 255,
        'unique' => true
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
        'length' => 22
    ],
    'activated' => [
        'type' => 'enum',
        'data' => "'0','1'",
        'default' => '0'
    ],
    'banned' => [
        'type' => 'enum',
        'data' => "'0','1'",
        'default' => '0'
    ],
    'ban_reason' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'ban_expire' => [
        'null' => true
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

### GENERATE A RANDOM SALT FOR THE ADMIN USER
$salt = substr( md5( rand( 0, 9999999 ) ), 0, 22 );

### INSERT THE ADMIN USER WITH GIVEN CREDENTIALS
$db->table( prefix( 'users' ) )->insert( [
    'email' => $_POST[ 'admin_email' ],
    'displayname' => $_POST[ 'admin_name' ],
    'password' => crypt( $_POST[ 'admin_password' ], '$2y$10$' . $salt . '$' ),
    'salt' => $salt,
    'activated' => '1',
    'banned' => '0',
    'ban_reason' => '',
    'ban_expire' => '',
    'last_login' => '-1',
    'registered' => time(),
    'last_ip' => '',
    'register_ip' => $_SERVER[ 'REMOTE_ADDR' ]
] );

### GETTING THE ADMIN USER ID
$uid = $db->mysqli->insert_id;

### CREATE USER PERMISSIONS TABLE
$db->createTable( prefix( 'user_permissions' ), [
    'id' => [
        'unique' => true   
    ],
    'permissions' => [
        'type' => 'text',
        'length' => NULL,
    ]
], [ 'ifNotExists' => 'true' ] );

### INSERT ADMIN PERMISSIONS
$db->table( prefix( 'user_permissions' ) )->insert( [ 'id' => $uid, 'permissions' => '*' ] );

### CREATE REGISTRATION ACTIVATION CODES TABLE
$db->createTable( prefix( 'activation_keys' ), [
    'id' => [ 'primary' => true ],
    'activation_code' => [ 'length' => 5 ]
], [ 'ifNotExists' => 'true' ] );

### CREATE PROJECTS TABLE
$db->createTable( prefix( 'projects' ), [
    'id' => [
        'autoIncrement' => true,
        'primary' => true
    ],
    'name' => [
        'type' => 'varchar',
        'length' => 255,
        'unique' => true
    ],
    'short' => [
        'type' => 'varchar',
        'length' => 4,
        'unique' => true
    ],
    'description' => [
        'type' => 'text',
        'length' => null
    ],
    'enabled' => [
        'type' => 'enum',
        'data' => "'0','1'",
        'default' => '0'
    ],
    'project_lead' => [],
    'date_created' => []
], [ 'ifNotExists' => true ] );

### CREATE ISSUES TABLE
$db->createTable( prefix( 'issues' ), [
    'id' => [
        'autoIncrement' => 'true',
        'primary' => true
    ],
    'project' => [],
    'name' => [
        'type' => 'varchar',
        'length' => 255
    ],
    'description' => [
        'type' => 'text',
        'length' => null
    ],
    'label' => [
        'null' => true
    ],
    'security' => [
        'type' => 'enum',
        'data' => "'public','private'",
        'default' => 'public',
    ],
    'priority' => [
        'type' => 'enum',
        'data' => "'low','medium','high'",
        'default' => 'medium',
    ],
    'author' => [],
    'date_created' => [],
    'date_edited' => [
        'null' => true
    ],
    'editor' => [
        'null' => true
    ],
    'status' => [
        'type' => 'enum',
        'data' => "'open','closed','invalid'"
    ]
], [ 'ifNotExists' => true ] );

### CREATE LABELS TABLE
$db->createTable( prefix( 'labels' ), [
    'id' => [
        'autoIncrement' => true,
        'primary' => true
    ],
    'label' => [
        'type' => 'varchar',
        'length' => 255,
        'unique' => true
    ],
    'txtcolor' => [ 
        'type' => 'varchar',
        'length' => 20
    ],
    'bgcolor' => [ 
        'type' => 'varchar',
        'length' => 20
    ]
], [ 'ifNotExists' => true ] );

### INSERT DEFAULT ISSUES
$db->table( prefix( 'labels' ) )->insertMany( [
    [ 'label' => 'Bug', 'txtcolor' => 'FFFFFF', 'bgcolor' => '610B0B' ],
    [ 'label' => 'Bug + Fix', 'txtcolor' => 'FFFFFF', 'bgcolor' => 'A5DF00' ],
    [ 'label' => 'Feature request', 'txtcolor' => 'FFFFFF', 'bgcolor' => 'F4FA58' ],
], [ 'existsKey' => 'label' ] );

### CREATE COMMENTS TABLE
$db->createTable( prefix( 'comments' ), [
    'id' => [
        'autoIncrement' => true,
        'primary' => true
    ],
    'author' => [],
    'issue' => [],
    'date_created' => [],
    'date_edited' => [
        'null' => true
    ],
    'editor' => [
        'null' => true
    ],
    'removed' => [
        'type' => 'enum',
        'data' => "'0','1'",
        'default' => '0'
    ],
], [ 'ifNotExists' => true ] );

### CREATE DISABLED PLUGINS TABLE
$db->createTable( prefix( 'plugins_disabled' ), [
    'name' => [
        'type' => 'varchar',
        'length' => 255,
        'unique' => true
    ]
], [ 'ifNotExists' => true ] );

$template = 'installed';

### DRAW TEMPLATE
draw_template:
$tpl = new RainTPL();

RainTPL::configure( 'tpl_ext', 'tpl' );
RainTPL::configure( 'tpl_dir', 'tpl/' );

if( $template == 'installed' ) $tpl->assign( 'settings', $db->table( prefix( 'settings' ) )->select( '*' )->getAll( 'setting', 'value' ) );
if( $template == 'installed' ) $tpl->assign( 'admin', $db->table( prefix( 'users' ) )->select( '*', [ 'where' => 'id = \'' . $uid . '\'' ] )->getEntry( 0 )->getFields() );

if( isset( $error ) ) $tpl->assign( 'error', $error );

$tpl->assign( 'mysql_server', $_POST[ 'db_host' ] );
$tpl->assign( 'mysql_username', $_POST[ 'db_username' ] );

$tpl->draw( $template );

if( isset( $db ) )
{
    echo '<pre>';
    var_dump( $db->getLastQueries() );
    echo '</pre>';
}
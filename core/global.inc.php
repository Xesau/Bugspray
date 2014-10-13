<?php

/* COPYRIGHT BUGSPRAY PROJECT 2014 */

if( !defined( 'CDIR' ) )
	exit( 'There is no <i>current working directory</i> defined' );

session_start();

require_once CDIR . '/core/functions.inc.php';

require_once CDIR . '/core/config.inc.php';
require_once CDIR . '/core/rain.tpl.class.php';
require_once CDIR . '/core/miqrodb.class.php';
require_once CDIR . '/core/pagedata.class.php';

$db = new MiqroDB ( new MySQLi( $db_host, $db_user, $db_password, $db_database ) );

require_once CDIR . '/language/' . setting( 'language', 'english' )  . '.lang.php';

RainTPL::$tpl_ext = 'tpl';
RainTPL::$tpl_dir = 'theme/bugspray/';
RainTPL::$path_replace = true;
raintpl::$base_url = setting( 'base_url' ) . '/';

$tpl = new RainTPL();

$tpl->assign( 'lang', $l );
$tpl->assign( 'settings', $db->table( prefix( 'settings' ) )->select( '*' )->getAll( 'setting', 'value' ) );
$tpl->assign( 'projects', $db->table( prefix( 'projects' ) )->select( '*' )->getAssoc( 'id' ) );
$tpl->assign( 'loggedIn', !empty( $_SESSION[ 'user_id' ] ) );
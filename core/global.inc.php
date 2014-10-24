<?php

/* COPYRIGHT BUGSPRAY PROJECT 2014 */

if( !defined( 'CDIR' ) )
    exit( 'There is no <i>current working directory</i> defined' );

if( !defined( 'IN_ADMIN' ) )
    define( 'IN_ADMIN', false );

if( !defined( 'NO_TPL' ) )
    define( 'NO_TPL', false );

session_start();

define( 'LOGGED_IN', ( IN_ADMIN === true
                        ? !empty( $_SESSION[ 'admin_user_id' ] )
                        : !empty( $_SESSION[ 'user_id'       ] ) ) );

require_once CDIR . '/core/config.inc.php';
require_once CDIR . '/core/functions.inc.php';

require_once CDIR . '/core/miqrodb.class.php';
require_once CDIR . '/core/db.loader.php';

if( !NO_TPL ) require_once CDIR . '/core/pagedata.class.php';
if( !NO_TPL ) require_once CDIR . '/core/rain.tpl.class.php';

$db = new MiqroDB ( new MySQLi( $db_host, $db_user, $db_password, $db_database ) );
$db->debug = (bool)setting( 'debug_mode' );

DB::loaderInit($db);

require_once CDIR . '/language/' . setting( 'language', 'english' )  . '.lang.php';

if( !NO_TPL )
{   RainTPL::configure( 'tpl_ext', 'tpl' );
    RainTPL::configure( 'tpl_dir', ( IN_ADMIN === false ? 'theme/' . setting( 'theme' ) : 'tpl' ) . '/' );
    RainTPL::configure( 'path_replace', true );
    RainTPL::configure( 'base_url', setting( 'base_url' ) . ( IN_ADMIN === true ? '/admin' : '' ) . '/' );

    $tpl = new RainTPL();
    
    $tpl->assign( 'lang', $l );
    $tpl->assign( 'settings', $db->table( prefix( 'settings' ) )->select( '*' )->getAll( 'setting', 'value' ) );
    $tpl->assign( 'projects', $db->table( prefix( 'projects' ) )->select( '*' )->getAssoc( 'id' ) );
    $tpl->assign( 'server', [ 'available_themes' => glob( CDIR . '/theme/*', GLOB_ONLYDIR ), 'available_languages' => glob( CDIR . '/language/*.lang.php' ) ] );
    $tpl->assign( 'loggedIn', LOGGED_IN );
}
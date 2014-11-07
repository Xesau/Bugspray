<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'IN_INSTALL', true );

require_once CDIR . '/core/global.inc.php';

if( file_exists( CDIR . '/core/config.inc.php' ) ) header( 'Location: ../' );

RainTPL::configure( 'tpl_ext', 'tpl' );
RainTPL::configure( 'tpl_dir', 'tpl/' );

$tpl = new RainTPL();
$tpl->assign( 'current_url', explode( '?', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") );
$tpl->assign( 'server', [ 'available_themes' => glob( CDIR . '/theme/*', GLOB_ONLYDIR ), 'available_languages' => glob( CDIR . '/language/*.lang.php' ) ] );

$tpl->draw( 'install' );
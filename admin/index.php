<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'IN_ADMIN', true );

require_once CDIR . '/core/global.inc.php';

$page = ( !empty( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : ( LOGGED_IN ? 'home' : 'login' ) );
$page = ( $page == 'login' && LOGGED_IN ? 'home' : $page );
$page = ( $page !== 'login' && !LOGGED_IN ? 'login' : $page );

if( !empty( $_GET[ 'id' ] ) )
    $tpl->assign( 'id', $_GET[ 'id' ] );
else
    $tpl->assign( 'id', NULL );

switch( $page )
{
    case 'home':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'home' )->setTitle( $l[ 'admin' ][ 'home' ] )->toArray() );
        $tpl->assign( 'page', 'home' );
        break;
    
    case 'login':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'login' )->setTitle( $l[ 'login' ] )->toArray() );
        $tpl->assign( 'page', 'home' );
        break;
    
    case 'settings':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'settings' )->setTitle( $l[ 'admin' ][ 'settings' ] )->toArray() );
        $tpl->assign( 'page', 'settings' );
        break;
    
    case 'labels':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
    
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'label' ) );
        break;
    
    case 'projects':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'projects' )->setTitle( $l[ 'admin' ][ 'projects' ] )->toArray() );
        $tpl->assign( 'page', 'projects' );
    
        $limit = ( !empty( $_GET[ 'id' ] ) ? ( $_GET[ 'id' ] + 1 ) * 30 : 30 );
        $start = $limit - 30; 
        
        $tpl->assign( 'projects', DB::$i->table( prefix( 'projects' ) )->select( '*', [ 'limit' => $start . ',' . $limit ] )->getAssoc( 'id' ) );
        $tpl->assign( 'limit', $limit );
        $tpl->assign( 'count', DB::$i->table( prefix( 'projects' ) )->count() );
        break;
    
    case 'users':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'users' )->setTitle( $l[ 'admin' ][ 'users' ] )->toArray() );
        $tpl->assign( 'page', 'users' );
        
        $limit = ( !empty( $_GET[ 'id' ] ) ? ( $_GET[ 'id' ] + 1 ) * 30 : 30 );
        $start = $limit - 30; 
        
        $tpl->assign( 'users', DB::$i->table( prefix( 'users' ) )->select( '*', [ 'limit' => $start . ',' . $limit ] )->getAssoc( 'id' ) );
        $tpl->assign( 'limit', $limit );
        $tpl->assign( 'count', DB::$i->table( prefix( 'users' ) )->count() );
        break;
    
    case 'logout':
        unset( $_SESSION[ 'admin_user_id' ] );
        header( 'Location: ' . setting( 'base_url' ) . '/admin' );
        break;
    
    default:
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'error' )->setTitle( $l[ 'error' ] )->toArray() );
        $tpl->assign( 'page', '' );
        break;
}


$tpl->draw( 'admin' );
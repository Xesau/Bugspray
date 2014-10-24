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
        if( !hasPermission( USERID, 'bt_update_settings' ) ) $tpl->assign( 'status', [ 'type' => 'info', 'language_key' => 'no_edit_permission' ] );
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
    
    case 'banuser':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'users' )->setTitle( $l[ 'admin' ][ 'users' ] )->toArray() );
        $tpl->assign( 'page', 'users' );
        
        if( hasPermission( $_SESSION[ 'admin_user_id' ], 'bt_ban' ) )
        {
            if( !empty( $_POST[ 'reason' ] ) && !empty( $_POST[ 'expire' ] ) )
                if( userExists( $_GET[ 'id' ] ) && !hasPermission( $_GET[ 'id' ], 'bt_unbannable' ) )
                {   $table = DB::$i->table( prefix( 'users' ) );
                    $table->update( [
                        'banned' => 1,
                        'ban_reason' => $_POST[ 'reason' ],
                        'ban_expire' => $_POST[ 'expire' ]
                    ], [ 'where' => 'id = \'' . DB::$i->escape( $_GET[ 'id' ] ) . '\'' ] );
                    $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'user_banned' ] );
                }
                else
                    $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'doest_exist' ] );
            else
               $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'data_missing' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'no_permission' ] );
        break;
    
    case 'save_settings':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'settings' )->setTitle( $l[ 'admin' ][ 'settings' ] )->toArray() );
        $tpl->assign( 'page', 'settings' );    
    
        if( hasPermission( $_SESSION[ 'admin_user_id' ], 'bt_update_settings' ) )
        {
            if( !empty( $_POST[ 'site_name' ] ) && !empty( $_POST[ 'base_url' ] ) && !empty( $_POST[ 'debug_mode' ] )
                    && !empty( $_POST[ 'issue_labels' ] ) && !empty( $_POST[ 'admin_email' ] ) && !empty( $_POST[ 'version' ] )
                    && !empty( $_POST[ 'theme' ] ) && !empty( $_POST[ 'language' ] ) )
            {   DB::$i->table( prefix( 'settings' ) )->updateWhere(
                    [   'site_name' => $_POST[ 'site_name' ],
                        'base_url' => $_POST[ 'base_url'],
                        'debug_mode' => $_POST[ 'debug_mode'],
                        'issue_labels' => $_POST[ 'issue_labels'],
                        'admin_email' => $_POST[ 'admin_email'],
                        'issue_project_version' => $_POST[ 'version'],
                        'theme' => $_POST[ 'theme'],
                        'language' => $_POST[ 'language']
                    ], 'value', 'setting'
                );
                $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'saved' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'data_missing' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'no_permission' ] );
        break;
    
    case 'newlabel':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
    
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'label' ) );
        
        if( hasPermission( $_SESSION[ 'admin_user_id' ], 'bt_labels_create' ) )
        {
            
        }
        else
            $tpl->assign( 'status', [ 'type' => 'error', 'language_key' => 'no_permission' ] );
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
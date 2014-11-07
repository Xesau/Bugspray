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

$tpl->assign( 'plugin_pages', PluginManager::getAdminPages( true ) );

$tpl->assign( 'current_path', [ 'home' => $l[ 'admin' ][ 'home' ] ] );

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
        if( !hasPermission( USERID, 'bs_update_settings' ) ) $tpl->assign( 'status', [ 'type' => 'info', 'language_key' => 'no_edit_permission' ] );
        break;
    
    case 'labels':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
    
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
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
    
    case 'plugins':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'plugins' )->setTitle( $l[ 'admin' ][ 'plugins' ] )->toArray() );
        $tpl->assign( 'page', 'plugins' );
        
        $tpl->assign( 'plugins', PluginManager::getPlugins() );
        $tpl->assign( 'disabledPlugins', PluginManager::getDisabledPlugins() );
        break;
    
    case 'banuser':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'users' )->setTitle( $l[ 'admin' ][ 'users' ] )->toArray() );
        $tpl->assign( 'page', 'users' );
        
        if( hasPermission( USERID, 'bs_ban' ) )
        {
            if( !empty( $_POST[ 'reason' ] ) && !empty( $_POST[ 'expire' ] ) )
                if( userExists( $_GET[ 'id' ] ) && !hasPermission( $_GET[ 'id' ], 'bs_unbannable' ) )
                {   $table = DB::$i->table( prefix( 'users' ) );
                    $table->update( [
                        'banned' => 1,
                        'ban_reason' => $_POST[ 'reason' ],
                        'ban_expire' => $_POST[ 'expire' ]
                    ], [ 'where' => 'id = \'' . DB::$i->escape( $_GET[ 'id' ] ) . '\'' ] );
                    $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'user_banned' ] );
                }
                else
                    $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'doest_exist' ] );
            else
               $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'data_missing' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
        break;
    
    case 'save_settings':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'settings' )->setTitle( $l[ 'admin' ][ 'settings' ] )->toArray() );
        $tpl->assign( 'page', 'settings' );    
    
        if( hasPermission( USERID, 'bs_update_settings' ) )
        {
            if( !empty( $_POST[ 'site_name' ] ) && !empty( $_POST[ 'base_url' ] ) && !empty( $_POST[ 'debug_mode' ] )
                    && !empty( $_POST[ 'issue_labels' ] ) && !empty( $_POST[ 'admin_email' ] ) && !empty( $_POST[ 'version' ] )
                    && !empty( $_POST[ 'theme' ] ) && !empty( $_POST[ 'language' ] ) )
            {   $langexists = file_exists( CDIR . '/language/' . $_POST[ 'language' ] . '.lang.php'  );
                DB::$i->table( prefix( 'settings' ) )->updateWhere(
                    [   'site_name' => $_POST[ 'site_name' ],
                        'base_url' => $_POST[ 'base_url' ],
                        'debug_mode' => $_POST[ 'debug_mode' ],
                        'issue_security' => $_POST[ 'security' ],
                        'issue_priority' => $_POST[ 'issue_priority' ],
                        'issue_labels' => $_POST[ 'issue_labels' ],
                        'issue_project_version' => $_POST[ 'version' ],
                        'admin_email' => $_POST[ 'admin_email' ],
                        'issue_project_version' => $_POST[ 'version' ],
                        'theme' => $_POST[ 'theme' ],
                        $langexists ? 'language' : '@dont_use@' => $langexists ? $_POST[ 'language' ] : '@dont_use@'
                    ], 'value', 'setting'
                );
             
                $tpl->assign( 'settings', DB::table( prefix( 'settings' ) )->select( '*' )->getAll( 'setting', 'value' ) );
                $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'saved' ] );
                require_once CDIR . '/language/' . $_POST[ 'language' ] . '.lang.php';
                $tpl->var['lang'] = $l;
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'data_missing' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'info', 'language_key' => 'no_edit_permission' ] );
        break;
    
    case 'deletelabel':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
        
        if( isset( $_GET[ 'id' ] ) )
            if( hasPermission( USERID, 'bs_labels_remove' ) )
            {
                DB::$i->table( prefix( 'labels' ) )->delete( [ 'where' => 'id = \'' . DB::$i->escape( $_GET[ 'id' ] ) . '\'' ] );
                $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'removed' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
        else
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'data_missing' ] );
        
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
        break;
    
    case 'newlabel':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
        
        if( hasPermission( USERID, 'bs_labels_create' ) )
        {
            if( !empty( $_POST[ 'label_name']  ) && !empty( $_POST[ 'text_color']  ) && !empty( $_POST[ 'background_color']  ) )
            {
                if( DB::table( prefix( 'labels' ) )->select( 'id', [ 'where' => 'label = \'' . DB::escape( $_POST[ 'label_name' ] ) . '\'' ] )->size() == 0 )
                {
                    DB::table( prefix( 'labels' ) )->insert( [
                            'label' => $_POST[ 'label_name' ],
                            'txtcolor' => $_POST[ 'text_color' ],
                            'bgcolor' => $_POST[ 'background_color' ]
                    ]);
                    $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'created' ] );
                }
                else
                    $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'exists_already' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'data_missing' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
        
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
        break;
    
    case 'modifylabel':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
        $tpl->assign( 'page', 'labels' );
        
        if( hasPermission( USERID, 'bs_labels_create' ) )
            {
                if( !empty( $_POST[ 'label_name']  ) && !empty( $_POST[ 'text_color']  ) && !empty( $_POST[ 'background_color']  ) )
                {
                    DB::$i->table( prefix( 'labels' ) )->updateFields( [
                            'label' => $_POST[ 'label_name' ],
                            'txtcolor' => $_POST[ 'text_color' ],
                            'bgcolor' => $_POST[ 'background_color' ]
                    ], [ 'where' => 'id = \'' . DB::$i->escape( $_POST[ 'label' ] ) . '\'' ] );
                    $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'updated' ] );
                }
                else
                    $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'data_missing' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
    
        $tpl->assign( 'labels', DB::$i->table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
        break;
    
    case 'logout':
        unset( $_SESSION[ 'admin_user_id' ] );
        header( 'Location: ' . setting( 'base_url' ) . '/admin' );
        break;
    
    case 'disable_plugin':
        if( hasPermission( USERID, 'bs_plugin_disable' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && PluginManager::hasPlugin( $_GET[ 'id' ] ) )
            {
                DB::table( prefix( 'plugins_disabled' ) )->insert( [ 'name' => $_GET[ 'id' ] ] );
                $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'disabled' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'plugin_doesnt_exist' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
        
        $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'admin' ][ 'plugins' ] )->setTemplate( 'plugins' )->toArray() );
        $tpl->assign( 'page', 'plugins' );
        $tpl->assign( 'plugins', PluginManager::getPlugins() );
        break;
    
    case 'enable_plugin':
        if( hasPermission( USERID, 'bs_plugin_enable' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && PluginManager::hasPluginDisabled( $_GET[ 'id' ] ) )
            {
                DB::table( prefix( 'plugins_disabled' ) )->delete( [ 'where' => 'name = \'' . $_GET[ 'id' ] . '\'' ] );
                $tpl->assign( 'status', [ 'type' => 'success', 'language_key' => 'enabled' ] );
            }
            else
                $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'plugin_doesnt_exist' ] );
        }
        else
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'no_permission' ] );
        
        $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'admin' ][ 'plugins' ] )->setTemplate( 'plugins' )->toArray() );
        $tpl->assign( 'page', 'plugins' );
    
        $tpl->assign( 'plugins', PluginManager::getPlugins() );
        $tpl->assign( 'disabledPlugins', PluginManager::getDisabledPlugins() );
        break;
    
    case 'plugin':
        $tpl->assign( 'page', '' );
        $tpl->assign( 'pagedata', ( new PageData )->setTemplate( 'error' )->setTitle( $l[ 'error' ] )->toArray() );
        try
        {
            if( PluginManager::hasAdminPage( $_GET[ 'id' ] ) )
            {
                $page = PluginManager::getAdminPage( $_GET[ 'id' ] );
                $tpl->assign( 'pagedata', $page->toArray() );
                $tpl->assign( 'page', $_GET[ 'id' ] );
                $tpl->assign( $plugin->getTemplateVariables( $_GET[ 'id' ] ) );
            }
        }
        catch ( Exception $e ) {}
        break;
    
    default:
        $tpl->assign( 'pagedata', ( new PageData )->setTemplate( 'error' )->setTitle( $l[ 'error' ] )->toArray() );
        $tpl->assign( 'page', '' );
        break;
}


$tpl->draw( 'admin' );
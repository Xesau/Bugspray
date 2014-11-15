<?php

define( 'CDIR', realpath( dirname( __FILE__ ) . '/..' ) );
define( 'IN_ADMIN', true );

require_once CDIR . '/core/global.inc.php'; 

$page = ( !empty( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : ( LOGGED_IN ? 'home' : 'login' ) );
$page = ( $page == 'login' && LOGGED_IN ? 'home' : $page );
$page = ( $page !== 'login' && !LOGGED_IN ? 'login' : $page );

if( LOGGED_IN )
{
    $page = ( hasPermission( USERID, 'bs_admin' ) ? $page : 'login' );
    if( !hasPermission( USERID, 'bs_admin' ) )
        showMessage( 'info', 'no_permission' );
}

if( !empty( $_GET[ 'id' ] ) )
    $tpl->assign( 'id', $_GET[ 'id' ] );
else
    $tpl->assign( 'id', NULL );

$tpl->assign( 'plugin_pages', PluginManager::getAdminPages( true ) );

$tpl->assign( 'current_path', [ 'home' => $l[ 'admin' ][ 'home' ] ] );

switch( $page )
{
    ### NORMAL PAGES
    case 'home':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'home' )->setTitle( $l[ 'admin' ][ 'home' ] )->toArray() );
        $tpl->assign( 'page', 'home' );
        break;
    
    case 'login':
        $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'login' )->setTitle( $l[ 'login' ] )->toArray() );
        $tpl->assign( 'page', 'home' );
    
        if( isset( $_SESSION[ 'login_error' ] ) )
        {
            showMessage( 'danger', $_SESSION[ 'login_error' ] );
            $tpl->assign( 'id', ( isset( $_SESSION[ 'login_email' ] ) ? $_SESSION[ 'login_email' ] : null ) );
        }
        unset( $_SESSION[ 'login_error' ] );
        break;
    
    case 'settings':
        assignVars( 'settings' );
        break;
    
    case 'labels':
        assignVars( 'labels' );
        break;
    
    case 'projects':
        assignVars( 'projects' );
        break;
    
    case 'users':
        assignVars( 'users' );
        break;
    
    case 'plugins':
        assignVars( 'plugins' );
        break;
    
    
    ### EDIT PAGES
    case 'project':
        if( empty( $_GET[ 'id' ] ) || ( !empty( $_GET[ 'id' ] ) && DB::table( prefix( 'projects' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->size() < 1 ) )
        {
            assignVars( 'projects' );
            showMessage( 'danger','doesnt_exist' );
        }
        else
        {
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'edit_project' )->setTitle( $l[ 'admin' ][ 'edit_project' ] )->toArray() );
            $tpl->assign( 'page', 'projects' ); 
            
            $tpl->assign( 'project', DB::table( prefix( 'projects' ) )->select( '*', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->getEntry( 0 )->getFields() );
            $tpl->assign( 'hasImage', file_exists( CDIR . '/content/project_imgs/' . $_GET[ 'id' ] . '.png' ) );
        }
        
        $lead_emails =  DB::table( prefix( 'users' ) )->select( 'email' )->getEntries();
        foreach($lead_emails as &$email) $email = $email[0];
        $tpl->assign( 'lead_emails', '"' . implode('","', $lead_emails) . '"' );
        break;
    
    case 'user':
        if( empty( $_GET[ 'id' ] ) || ( !empty( $_GET[ 'id' ] ) && DB::table( prefix( 'users' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->size() < 1 ) )
        {
            assignVars( 'users' );
            showMessage( 'danger','doesnt_exist' );
        }
        else
        {
            if( !hasPermission( USERID, 'bs_users' ) )
            {
                $tpl->assign( 'disabled', true );
                showMessage( 'info', 'no_edit_permission' );
            }
            else
                $tpl->assign( 'disabled', false );
            
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'edit_user' )->setTitle( $l[ 'admin' ][ 'edit_user' ] )->toArray() );
            $tpl->assign( 'page', 'users' ); 

            $tpl->assign( 'user', DB::table( prefix( 'users' ) )->select( '*', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->getEntry( 0 )->getFields() );
            $tpl->assign( 'hasImage', file_exists( CDIR . '/content/avatar/' . $_GET[ 'id' ] . '.png' ) );
        }
        break;
    
    case 'new_project':
        $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'admin' ][ 'new_project' ] )->setTemplate( 'new_project' )->addJS( 'http://code.jquery.com/ui/1.11.2/jquery-ui.js' )->toArray() );
        $tpl->assign( 'page', 'projects' );
        $lead_emails =  DB::table( prefix( 'users' ) )->select( 'email' )->getEntries();
        foreach($lead_emails as &$email) $email = $email[0];
        $tpl->assign( 'lead_emails', '"' . implode('","', $lead_emails) . '"' );
        break;
    
    ### ACTIONS
    case 'save_project':
        if( hasPermission( USERID, 'bs_projects' ) )
        {
            if( empty( $_GET[ 'id' ]  ) || DB::table( prefix( 'projects' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->size() < 1 )
                showMessage( 'danger','doesnt_exist' );
            else
            {
                if( DB::table( prefix( 'projects' ) )
                   ->select( 'id',
                        [ 'where' => '( name = \'' . DB::escape( $_POST[ 'name' ] ) . '\' OR short = \'' . DB::escape( $_POST[ 'short'] ) . '\' ) AND id != \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ]
                    )->size() > 0 )
                    showMessage( 'danger', 'exists_already' );
                else
                {
                    if( !empty( $_POST[ 'name' ] ) && !empty( $_POST[ 'short' ] ) && !empty( $_POST[ 'description' ] ) && !empty( $_POST[ 'project_lead' ] ) )
                    {
                        DB::table( prefix( 'projects' ) )->updateFields( [
                            'name' => $_POST[ 'name' ],
                            'short' => $_POST[ 'short' ],
                            'description' => $_POST[ 'description' ],
                            'project_lead' => userDataByEmail( $_POST[ 'project_lead' ], 'id' )
                        ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );

                        if( !empty( $_FILES[ 'file' ] ) )
                            move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], CDIR . '/content/project_imgs/' . $_GET[ 'id' ] . '.png' );

                        showMessage( 'success', 'updated' );
                    }
                    else
                        showMessage( 'danger', 'data_missing' );
                }
            }
        }
        else
            showMessage( 'danger', 'no_permisison' );
    
        # Set ID to 0 for page selector
        $_GET[ 'id' ] = 0;
        assignVars( 'projects' );
        break;
    
    case 'save_new_project':
        if( hasPermission( USERID, 'bs_projects' ) )
        {
            if( !empty( $_POST[ 'name' ] ) && !empty( $_POST[ 'short' ] ) && !empty( $_POST[ 'description' ] ) && !empty( $_POST[ 'lead' ] ) )
            {
                if( DB::table( prefix( 'projects' ) )
                   ->select( 'id', [ 'where' => 'name = \'' . DB::escape( $_POST[ 'name' ] ) . '\' OR short = \'' . DB::escape( $_POST[ 'short '] ) . '\'' ] )->size() > 0 )
                    showMessage( 'danger', 'exists_already' );
                ### TODO: MAKE SOME WAY OF GO BACK
                else
                {
                    DB::table( prefix( 'projects' ) )->insert( [
                        'name' => $_POST[ 'name' ],
                        'short' => $_POST[ 'short' ],
                        'description' => $_POST[ 'description' ],
                        'project_lead' => userDataByEmail( $_POST[ 'lead' ], 'id' ),
                        'date_created' => time()
                    ] );
                    
                    if( !empty( $_FILES[ 'file' ] ) )
                        move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], CDIR . '/content/project_imgs/' . DB::mysqli()->insert_id . '.png' );
                    
                    showMessage( 'success', 'created' );
                }
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permisison' );
        
        assignVars( 'projects' );
        break;
    
    case 'remove_avatar':
        if( hasPermission( USERID, 'bs_users' ) )
        {
            if( !empty( $_GET[ 'id' ] ) )
            {
                @unlink( CDIR . '/content/avatar/' . $_GET[ 'id' ] . '.png' );
                showMessage( 'success', 'picture_removed' );
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permisison' );
        
        $_GET[ 'id' ] = 0;
        assignVars( 'users' );
        break;
    
    case 'remove_project_img':
        if( hasPermission( USERID, 'bs_projects' ) )
        {
            if( !empty( $_GET[ 'id' ] ) )
            {
                @unlink( CDIR . '/content/project_imgs/' . $_GET[ 'id' ] . '.png' );
                showMessage( 'success', 'picture_removed' );
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permisison' );
        
        $_GET[ 'id' ] = 0;
        assignVars( 'projects' );
        break;
        
    case 'disable_project':
        if( hasPermission( USERID, 'bs_projects' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && DB::table( prefix( 'projects' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] ) )
            {
                DB::table( prefix( 'projects' ) )->updateFields( [
                    'enabled' => '0'
                ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                
                showMessage( 'success', 'updated' );
            }
            else
                showMessage( 'danger', 'doesnt_exist' );
        }
        else
            showMessage( 'danger', 'no_permission' );
        
        $_GET[ 'id' ] = 0;
        assignVars( 'projects' );
        break;
    
    case 'enable_project':
        if( hasPermission( USERID, 'bs_projects' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && DB::table( prefix( 'projects' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] ) )
            {
                DB::table( prefix( 'projects' ) )->updateFields( [
                    'enabled' => '1'
                ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                
                showMessage( 'success', 'updated' );
            }
            else
                showMessage( 'danger', 'doesnt_exist' );
        }
        else
            showMessage( 'danger', 'no_permission' );
        
        $_GET[ 'id' ] = 0;
        assignVars( 'projects' );
        break;
    
    case 'banuser':
        if( hasPermission( USERID, 'bs_ban' ) )
        {
            if( !empty( $_POST[ 'reason' ] ) && !empty( $_POST[ 'expire' ] ) )
                if( userExists( $_GET[ 'id' ] ) && !hasPermission( $_GET[ 'id' ], 'bs_unbannable' ) )
                {   $table = DB::table( prefix( 'users' ) );
                    $table->update( [
                        'banned' => 1,
                        'ban_reason' => $_POST[ 'reason' ],
                        'ban_expire' => $_POST[ 'expire' ]
                    ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                    showMessage( 'success', 'user_banned' );
                }
                else
                    showMessage( 'danger', 'doest_exist' );
            else
               showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permission' );
    
        $_GET[ 'id' ] = 0;
        assignVars( 'users' );
        break;
    
    case 'save_user':
        if( hasPermission( USERID, 'bs_users' ) )
        {
            if( !empty( $_POST[ 'fullname' ] ) && !empty( $_POST[ 'email' ] ) )
            {
                if( DB::table( prefix( 'users' ) )->select( 'id', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->size() > 0 )
                {
                    DB::table( prefix( 'users' ) )->updateFields( [
                        'displayname' => $_POST[ 'fullname' ],
                        'email' => $_POST[ 'email' ]
                    ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                    
                    if( !empty( $_POST[ 'password' ] ) && !empty( $_POST[ 'password_repeat' ] ) )
                    {
                        if( $_POST[ 'password' ] === $_POST[ 'password_repeat'] )
                        {
                            $salt = DB::table( prefix( 'users' ) )->select( 'salt', [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] )->getEntry( 0 )->getField( 'salt' );
                            
                            DB::table( prefix( 'users' ) )->updateFields( [
                                'password' => password_hash( $_POST[ 'password' ], PASSWORD_BCRYPT, [ 'salt' => $salt ] )
                            ], [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                        }
                    }
                    
                    if( !empty( $_FILES[ 'file' ] ) )
                        move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], CDIR . '/content/avatar/' . $_GET[ 'id' ] . '.png' );   
                    
                    showMessage( 'success', 'updated' );
                }
                else
                    showMessage( 'danger', 'doesnt_exists' );
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permission' );
    
        $_GET[ 'id' ] = 0;
        assignVars( 'users' );
        break;
    
    case 'save_settings':
        if( hasPermission( USERID, 'bs_update_settings' ) )
        {
            if( !empty( $_POST[ 'site_name' ] ) && !empty( $_POST[ 'base_url' ] ) && !empty( $_POST[ 'debug_mode' ] )
                    && !empty( $_POST[ 'issue_labels' ] ) && !empty( $_POST[ 'admin_email' ] ) && !empty( $_POST[ 'version' ] )
                    && !empty( $_POST[ 'theme' ] ) && !empty( $_POST[ 'language' ] ) )
            {
                $langexists = file_exists( CDIR . '/language/' . $_POST[ 'language' ] . '.lang.php'  );
                DB::table( prefix( 'settings' ) )->updateWhere(
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
                showMessage( 'success', 'saved' );
                
                require_once CDIR . '/language/' . $_POST[ 'language' ] . '.lang.php';
                $tpl->var['lang'] = $l;
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'info', 'no_edit_permission' );
    
        assignVars( 'settings' );
        break;
    
    case 'deletelabel':
        if( isset( $_GET[ 'id' ] ) )
            if( hasPermission( USERID, 'bs_labels' ) )
            {
                DB::table( prefix( 'labels' ) )->delete( [ 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ] );
                showMessage( 'success', 'removed' );
            }
            else
                showMessage( 'danger', 'no_permission' );
        else
            showMessage( 'danger', 'data_missing' );
        
        assignVars( 'labels' );
        break;
    
    case 'newlabel':
        if( hasPermission( USERID, 'bs_labels' ) )
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
                    showMessage( 'success', 'created' );
                }
                else
                    showMessage( 'danger', 'exists_already' );
            }
            else
                showMessage( 'danger', 'data_missing' );
        }
        else
            showMessage( 'danger', 'no_permission' );
        
        assignVars( 'labels' );
        break;
    
    case 'modifylabel':
        if( hasPermission( USERID, 'bs_labels' ) )
            {
                if( !empty( $_POST[ 'label_name']  ) && !empty( $_POST[ 'text_color']  ) && !empty( $_POST[ 'background_color']  ) )
                {
                    DB::table( prefix( 'labels' ) )->updateFields( [
                            'label' => $_POST[ 'label_name' ],
                            'txtcolor' => $_POST[ 'text_color' ],
                            'bgcolor' => $_POST[ 'background_color' ]
                    ], [ 'where' => 'id = \'' . DB::escape( $_POST[ 'label' ] ) . '\'' ] );
                    showMessage( 'success', 'updated' );
                }
                else
                    showMessage( 'danger', 'data_missing' );
            }
            else
                showMessage( 'danger', 'no_permission' );
    
        assignVars( 'labels' );
        break;
    
    case 'disable_plugin':
        if( hasPermission( USERID, 'bs_plugins' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && PluginManager::hasPlugin( $_GET[ 'id' ] ) )
            {
                DB::table( prefix( 'plugins_disabled' ) )->insert( [ 'name' => $_GET[ 'id' ] ] );
                showMessage( 'success', 'disabled' );
            }
            else
                showMessage( 'danger', 'plugin_doesnt_exist' );
        }
        else
            showMessage( 'danger', 'no_permission' );
        
        PluginManager::updateDisables();
        assignVars( 'plugins' );
        break;
    
    case 'enable_plugin':
        if( hasPermission( USERID, 'bs_plugins' ) )
        {
            if( !empty( $_GET[ 'id' ] ) && PluginManager::hasPluginDisabled( $_GET[ 'id' ] ) )
            {
                DB::table( prefix( 'plugins_disabled' ) )->delete( [ 'where' => 'name = \'' . $_GET[ 'id' ] . '\'' ] );
                showMessage( 'success', 'enabled' );
            }
            else
                showMessage( 'danger', 'plugin_doesnt_exist' );
        }
        else
            showMessage( 'danger', 'no_permission' );
        
        PluginManager::updateDisables();
        assignVars( 'plugins' );
        break;
    
    ### LOGOUT
    case 'logout':
        unset( $_SESSION[ 'admin_user_id' ] );
        header( 'Location: ' . setting( 'base_url' ) . '/admin' );
        break;
    
    ### PLUGIN PAGES
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
                $tpl->assign( $page->getPlugin()->getTemplateVariables( $_GET[ 'id' ] ) );
            }
        }
        catch ( Exception $e ) {}
        break;
    
    ### ERROR 404 I HATE YOU
    default:
        $tpl->assign( 'pagedata', ( new PageData )->setTemplate( 'error' )->setTitle( $l[ 'error' ] )->toArray() );
        $tpl->assign( 'page', '' );
        break;
}

### SHARED PAGE INFORMATION SWITCH
function assignVars( $page )
{
    global $tpl;
    global $l;
    switch( $page )
    {
        case 'plugins':
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'plugins' )->setTitle( $l[ 'admin' ][ 'plugins' ] )->toArray() );
            $tpl->assign( 'page', 'plugins' );
            
            $tpl->assign( 'plugins', PluginManager::getPlugins() );
            $tpl->assign( 'disabledPlugins', PluginManager::getDisabledPlugins() );
            break;
        
        case 'projects':
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'projects' )->setTitle( $l[ 'admin' ][ 'projects' ] )->toArray() );
            $tpl->assign( 'page', 'projects' );
        
            if( !hasPermission( USERID, 'bs_projects' ) )
                showMessage( 'info', 'no_edit_permission' );

            $limit = ( !empty( $_GET[ 'id' ] ) ? ( $_GET[ 'id' ] + 1 ) * 30 : 30 );
            $start = $limit - 30; 

            $tpl->assign( 'projects', DB::table( prefix( 'projects' ) )->select( '*', [ 'limit' => $start . ',' . $limit ] )->getAssoc( 'id' ) );
            $tpl->assign( 'limit', $limit );
            $tpl->assign( 'count', DB::table( prefix( 'projects' ) )->count() );
            break;
        
        case 'labels':
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'labels' )->setTitle( $l[ 'admin' ][ 'labels' ] )->toArray() );
            $tpl->assign( 'page', 'labels' );

            $tpl->assign( 'labels', DB::table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
            break;
        
        case 'users':
            if( !hasPermission( USERID, 'bs_users' ) )
                showMessage( 'info', 'no_edit_permission' );
        
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'users' )->setTitle( $l[ 'admin' ][ 'users' ] )->toArray() );
            $tpl->assign( 'page', 'users' );

            $limit = ( !empty( $_GET[ 'id' ] ) ? ( $_GET[ 'id' ] + 1 ) * 30 : 30 );
            $start = $limit - 30; 

            $tpl->assign( 'users', DB::table( prefix( 'users' ) )->select( '*', [ 'limit' => $start . ',' . $limit ] )->getAssoc( 'id' ) );
            $tpl->assign( 'limit', $limit );
            $tpl->assign( 'count', DB::table( prefix( 'users' ) )->count() );
            break;
        
        case 'settings':
            $tpl->assign( 'pagedata', ( new PageData() )->setTemplate( 'settings' )->setTitle( $l[ 'admin' ][ 'settings' ] )->toArray() );
            $tpl->assign( 'page', 'settings' );
            if( !hasPermission( USERID, 'bs_update_settings' ) ) showMessage( 'info', 'no_edit_permission' );
            break;
    }
}

### SHOW MESSAGE ON TOP OF SCREEN
function showMessage( $type, $language_key )
{
    global $tpl;
    
    if( !isset( $tpl->var[ 'status' ] ) )
        $tpl->assign( 'status', [] );
    
    $tpl->var[ 'status' ][] = [ 'type' => $type, 'language_key' => $language_key ];
}

### RENDER
$tpl->draw( 'admin' );
<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

$path = ( isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : 'home' );
$tpl->assign( 'page', $path );

if( isset( $_GET[ 'id' ] ) ) $tpl->assign( 'element', $_GET[ 'id' ] ); else $tpl->assign( 'element', null );

switch ($path)
{
	case 'home':
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'home' ] )->setTemplate( 'home' )->toArray() );
		$tpl->assign( 'latest_issues', DB::table( prefix( 'issues' ) )->select( '*', array( 'limit' => '0,5', 'order' => 'id DESC' ) )->getAssoc( 'id' ) );
		break;
	
	case 'projects':
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'projects' ] )->setTemplate( 'projects' )->toArray() );
		break;
		
	case 'project':
		if( !empty( $_GET[ 'id' ] ) && 
			DB::table( prefix( 'projects' ) )->select( 'id', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->size() > 0 )
		{
			$tpl->assign( 'project', DB::table( prefix( 'projects' ) )->
						select( '*', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->
						getEntry( 0 )->getFields() );
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $tpl->var[ 'project' ][ 'name' ] )->setTemplate( 'project' )->toArray() );
			$tpl->assign( 'latest_issues', DB::table( prefix( 'issues' ) )->
						select( '*', [
                            'limit' => '0,5', 'order' => 'id DESC',
                            'where' => [
                                'project = \'' . DB::escape( $tpl->var[ 'project' ][ 'id' ] ) . '\'',
                                ( hasPermission( USERID, 'bs_private_issues' ) ? : 'security = \'public\'' )
                            ]
                        ] )->getAssoc( 'id' ) );
		}
		else
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
		
	case 'issue':
		if( !empty( $_GET[ 'id' ] ) && 
                DB::table( prefix( 'issues' ) )->select( 'id', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->size() > 0 )
		{
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'issue' ] )->setTemplate( 'issue' )->toArray() );
			$tpl->assign( 'issue', DB::table( prefix( 'issues' ) )->
					select( '*', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->
					getEntry( 0 )->getFields() );
		}
		else
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
		
	case 'login':
		if( LOGGED_IN )
			header( 'Location: ./' );
		
		if( isset( $_SESSION[ 'login_error' ] ) )
        	$tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'login_' . $_SESSION[ 'login_error' ] ] );
        
        if( isset( $_SESSION[ 'register_error' ] ) )
            $tpl->assign( 'status', [ 'type' => 'danger', 'language_key' => 'register_' . $_SESSION[ 'register_error' ] ] );
    
        if( isset( $_SESSION[ 'login_email' ] ) )
            $tpl->assign( 'login_email', $_SESSION[ 'login_email' ] );

        if( isset( $_SESSION[ 'register_fields' ] ) )
            $tpl->assign( 'register_fields', $_SESSION[ 'register_fields' ] );
        else
            $tpl->assign( 'register_fields', [] );
                             
        unset( $_SESSION[ 'login_error' ] );
        unset( $_SESSION[ 'register_error' ] );
        unset( $_SESSION[ 'login_email' ] );
    
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'login' ] )->setTemplate( 'login' )->toArray() );
		break;
	
	case 'logout':
		unset( $_SESSION[ 'user_id' ] );
		header( 'Location: ./');
		break;
	
    case 'activate':
        $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'activate_account' ] )->setTemplate( 'register_complete' )->toArray() );
        $tpl->assign( 'page', null );
        unset( $_POST[ 'register_fields' ] );
        break;
    
	case 'newissue':
		if( !empty( $_GET[ 'id' ] ) && 
			DB::table( prefix( 'projects' ) )->select( 'id', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->size() > 0 )
		{
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'new_issue' ] )->setTemplate( 'new_issue' )->toArray() );
			$tpl->assign( 'project', DB::table( prefix( 'projects' ) )->
						select( '*', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->
						getEntry( 0 )->getFields() );
			$tpl->assign( 'issue_labels', DB::table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'label' ) );
		}
		else
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
		
    case 'activate':
        if( !empty( $_GET[ 'id' ] ) )
        {
            
        }
        break;
    
	default:
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
}

$tpl->draw( 'bugspray' );

unset( $_SESSION[ 'login_error' ] );
unset( $_SESSION[ 'login_email' ] );

exit();
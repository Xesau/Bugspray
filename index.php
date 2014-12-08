<?php

define( 'CDIR', realpath( dirname( __FILE__ ) ) );

require_once CDIR . '/core/global.inc.php';

$path = ( isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : 'home' );
$tpl->assign( 'page', $path );
$request = array( 'projectOpen' => false, 'search' => '' );

if( isset( $_GET[ 'id' ] ) ) $tpl->assign( 'element', $_GET[ 'id' ] ); else $tpl->assign( 'element', null );

switch ($path)
{
	case 'home':
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'home' ] )->setTemplate( 'home' )->toArray() );
		$tpl->assign( 'latest_issues', getIssuesFor( USERID, 0, 5 ) );
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
			$tpl->assign( 'latest_issues', getIssuesFor( USERID, 0, 5, $_GET[ 'id' ] ) );
		}
		else
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
		
	case 'issue':
		if( !empty( $_GET[ 'id' ] ) && 
                DB::table( prefix( 'issues' ) )->select( 'id', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->size() > 0 )
		{
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'issue' ] )->setTemplate( 'issue' )->toArray() );
			$select = DB::table( prefix( 'issues' ) )->select( '*', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->getEntry( 0 )->getFields();
            if( $select[ 'security' ] == 'public' || !hasPermission( USERID, 'bs_private_issues' ) )
                $tpl->assign( 'issue', $select );
            else
                exit();
            
            $tpl->assign( 'comments', DB::table( prefix( 'comments' ) )->select( '*', array( 'where' => 'id = \'' . DB::escape( $_GET[ 'id' ] ) . '\'' ) )->getAssoc( 'id' ) );
		}
		else
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;

    case 'search':
        $tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'search_results' ] )->setTemplate( 'search' )->toArray() );
        $request[ 'search' ] = $_POST[ 'query' ];
        break;
	case 'login':
		if( LOGGED_IN )
			header( 'Location: ./' );
		
		if( isset( $_SESSION[ 'login_error' ] ) )
        	showMessage( 'danger', $_SESSION[ 'login_error' ] );
        
        if( isset( $_SESSION[ 'register_error' ] ) )
            showMessage( 'danger', $_SESSION[ 'register_error' ] );
    
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
        if( !empty( $_REQUEST[ 'id' ] ) )
        {
            $select  = DB::table( prefix( 'activation_keys' ) )->select( ['id', 'activation_code' ], array( 'where' => 'activation_code = \'' . DB::escape( $_REQUEST[ 'id' ] ) . '\'' ) );
            if( $select->size() > 0 && $_REQUEST[ 'id' ] == $select->getEntry( 0 )->getField( 'activation_code' ) )
            {
                DB::table( prefix( 'activation_keys' ) )->delete( [ 'where' => [
                    'activation_code = \'' . DB::escape( $_REQUEST[ 'id' ] ) . '\'',
                    'id = \'' . DB::escape( $select->getEntry( 0 )->getField( 'id' ) ) . '\''
                ] ] );
                
                showMessage( 'success', 'account_activated' );
                $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'login' ] )->setTemplate( 'login' )->toArray() );
                $tpl->assign( 'page', 'login' );
            }
            else
            {
                showMessage( 'danger', 'code_incorrect' );
                $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'activate_account' ] )->setTemplate( 'register_complete' )->toArray() );
                $tpl->assign( 'page', null );
            }
        }
        else
        {
            $tpl->assign( 'pagedata', ( new PageData )->setTitle( $l[ 'activate_account' ] )->setTemplate( 'register_complete' )->toArray() );
            $tpl->assign( 'page', null );
        }
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
			$tpl->assign( 'issue_labels', DB::table( prefix( 'labels' ) )->select( '*' )->getAssoc( 'id' ) );
		}
		else
			$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
    
	default:
		$tpl->assign( 'pagedata', ( new PageData() )->setTitle( $l[ 'error' ] )->setTemplate( 'error404' )->toArray() );
		break;
}


$tpl->assign( 'request', $request );
$tpl->draw( 'bugspray' );

unset( $_SESSION[ 'login_error' ] );
unset( $_SESSION[ 'login_email' ] );

exit();
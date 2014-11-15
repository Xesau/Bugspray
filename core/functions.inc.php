<?php

function prefix( $s ) { global $db_prefix; return $db_prefix . $s; }

function setting( $setting, $def = null )
{
	$select = DB::table( prefix( 'settings' ) )->select( 'value', array( 'where' => 'setting = \'' . DB::escape($setting) . '\'' ) ); 
	if( $select->size() > 0 )
		return $select->getEntry( 0 )->getField( 'value' );
	return $def;
}

function getProject( $projectID )
{
	$select = DB::table( prefix( 'projects' ) )->select( '*', array( 'where' => 'id = \'' . DB::escape( $projectID ) . '\'' ) );
	return ( $select->size() > 0 ? $select->getEntry( 0 )->getFields() : null );
}

function getIssue( $issueID )
{
	$select = DB::table( prefix( 'issues' ) )->select( '*', array( 'where' => 'id = \'' . DB::escape( $projectID ) . '\'' ) );
	return ( $select->size() > 0 ? $select->getEntry( 0 )->getFields() : null );	
}

function rpath ( $path )
{
	$m = [ 'home', 'projects', 'issues', 'project', 'issue', 'newissue', 'login', 'logout', 'register' ];
	return ( in_array( $path, $m ) ? $path : null );
}

function userPermissions( $userid )
{
    $select = DB::table( prefix( 'user_permissions' ) )->select( '*', [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] );
    return $select->size() > 0 ? explode( PHP_EOL, $select->getEntry( 0 )->getField( 'permissions' ) ) : [];
}

function hasPermission( $userid, $permission )
{
    $perms = userPermissions( $userid );
    return in_array( $permission, $perms ) || ( in_array( '*', $perms ) && !in_array( '-' . $permission, $perms ) );
}

/**
 * Get one or more field(s) of an user
 *
 * @param $userid integer User ID
 * @param $fields string|array The field(s)
 */
function userData( $userid, $fields )
{
    $select = DB::table( prefix( 'users' ) )->select( $fields, [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] )->getEntry( 0 );
    if( is_array( $fields ) )
       return $select->getFields();
    else
       return $select->getField( $fields );
}

/**
 * Get one or more field(s) of an user identified by e-mail adress
 *
 * @param $email string The user his e-mail adress
 * @param $fields string|array The field(s)
 */
function userDataByEmail( $email, $fields )
{
    $select = DB::table( prefix( 'users' ) )->select( $fields, [ 'where' => 'email = \'' . DB::escape( $email ) . '\'' ] )->getEntry( 0 );
    if( is_array( $fields ) )
       return $select->getFields();
    else
       return $select->getField( $fields );
}

/**
 * Get one or more field(s) of a project
 *
 * @param $project integer Project ID
 * @param $fields string|array The field(s)
 */
function projectData( $project, $fields )
{
    $select = DB::table( prefix( 'projects' ) )->select( $fields, [ 'where' => 'id = \'' . DB::escape( $project ) . '\'' ] )->getEntry( 0 );
    if( is_array( $fields ) )
       return $select->getFields();
    else
       return $select->getField( $fields );
}

/**
 * Get one or more field(s) of an issue
 *
 * @param $issue integer Issue ID
 * @param $fields string|array The field(s)
 */
function issueData( $issue, $fields )
{
    $select = DB::table( prefix( 'issues' ) )->select( $fields, [ 'where' => 'id = \'' . DB::escape( $issue ) . '\'' ] )->getEntry( 0 );
    if( is_array( $fields ) )
       return $select->getFields();
    else
       return $select->getField( $fields );
}

/**
 * Get a data field of $plugin
 */
function pluginData( Plugin $plugin, $field )
{
    switch( $field )
    {
        case 'name':
            return $plugin->getName();
        case 'website':
            return $plugin->getWebsite();
        case 'author':
            return $plugin->getAuthor();
        case 'version':
            return $plugin->getVersion();
    }
}

/**
 * Show a message at the top of the screen
 * (if theme supports it)
 */
function showMessage( $type, $language_key )
{
    global $tpl;
    
    if( !isset( $tpl->var[ 'status' ] ) )
        $tpl->assign( 'status', [] );
    
    $tpl->var[ 'status' ][] = [ 'type' => $type, 'language_key' => $language_key ];
}

?>
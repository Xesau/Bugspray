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
    return explode( PHP_EOL, DB::table( prefix( 'user_permissions' ) )->select( '*', [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] )->getEntry( 0 )->getField( 'permissions' ) );
}

function hasPermission( $userid, $permission )
{
    $perms = userPermissions( $userid );
    return in_array( $permission, $perms ) || in_array( '*', $perms );
}

function filename( $path, $cut = '' )
{
    return substr( basename( $path ), 0, -strlen( $cut ) );
}

function userData( $userid, $field )
{
    return DB::table( prefix( 'users' ) )->select( $field, [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] )->getEntry( 0 )->getField( $field );
}

function projectData( $userid, $field )
{
    return DB::table( prefix( 'projects' ) )->select( $field, [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] )->getEntry( 0 )->getField( $field );
}

function issueData( $userid, $field )
{
    return DB::table( prefix( 'issue' ) )->select( $field, [ 'where' => 'id = \'' . DB::escape( $userid ) . '\'' ] )->getEntry( 0 )->getField( $field );
}

?>
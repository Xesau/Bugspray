<?php


function prefix( $s ) { global $db_prefix; return $db_prefix . $s; }

function setting( $setting, $def = null )
{
	global $db;
	$select = $db->table( prefix( 'settings' ) )->select( 'value', array( 'where' => 'setting = \'' . $db->escape($setting) . '\'' ) ); 
	if( $select->size() > 0 )
		return $select->getEntry( 0 )->getField( 'value' );
	return $def;
}

function getProject( $projectID )
{
	global $db;
	$select = $db->table( prefix( 'projects' ) )->select( '*', array( 'where' => 'id = \'' . $db->escape( $projectID ) . '\'' ) );
	return ( $select->size() > 0 ? $select->getEntry( 0 )->getFields() : null );
}

function getIssue( $issueID )
{
	global $db;
	$select = $db->table( prefix( 'issues' ) )->select( '*', array( 'where' => 'id = \'' . $db->escape( $projectID ) . '\'' ) );
	return ( $select->size() > 0 ? $select->getEntry( 0 )->getFields() : null );	
}

function rpath ( $path )
{
	$m = [ 'home', 'projects', 'issues', 'project', 'issue', 'newissue', 'login', 'logout', 'register' ];
	return ( in_array( $path, $m ) ? $path : null );
}

?>
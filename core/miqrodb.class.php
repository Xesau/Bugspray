<?php

class MiqroDB
{
	
	public $mysqli;
	public static $debug = false;
	public static $lastError = '';
	
	public function __construct( $con )
	{
		if( $con == null )
			throw new MiqroException( 'Passed MySQLi object is null', 0 );
		
		elseif( $con->connect_error )
			throw new MiqroException( 'Passed MySQLi object is invalid', 1 );
		
		else
			$this->mysqli = $con;
	}
	
	public function table( $tablename )
	{
		$query = $this->mysqli->query( 'SHOW TABLES LIKE \'' . $this->escape( $tablename ) . '\'' );
		if( !empty( $this->mysqli->error ) )
			MiqroDB::$lastError = $this->mysqli->error;
		
		if( $query->num_rows == 1 )
			return new MiqroTable( $this, $tablename );
		else
			throw new MiqroException( 'The table ' . $tablename . ' does not exist', 2 );
	}
	
	public function escape( $value )
	{
		return $this->mysqli->real_escape_string( $value );
	}
	
	public function getTables()
	{
		$tables = array();
		$query = $this->mysqli->query( 'SHOW TABLES' );
		if( $query !== null )
			while( $table = $query->fetch_array() )
				$tables[] = $table[0];
		
		return $tables;
	}
	
}

class MiqroTable
{
	
	private $tablename;
	private $miqro;
	
	public function __construct( MiqroDB $db, $tablename ) 
	{
		$this->miqro = $db;
		$this->tablename = $tablename;
	}
	
	/**
	 * Update rows in the table
	 * 
	 * @param $update array The update data. Structure:
	 * 'field' => 'value'
	 * 
	 */
	public function update( $update, $options = array() )
	{
		if( !is_array( $options ) )
			throw new MiqroException( 'Options parameters not an array', 3 ); 
			
		$sql = 'UPDATE `$table` SET $data ';
		
		$sqldata = array();
		
		foreach( $update as $key => $value )
		{
			$sqldata[] = '`' . $this->miqro->escape( $key ) . '` = \'' . $this->miqro->escape( $value ) . '\'';
		}
		
		if( isset ( $options[ 'where' ] ) )
		{
			$sql .= 'WHERE ';
			if( !is_array( $options[ 'where' ] ) )
				$sql .= $this->miqro->escape( $options[ 'where' ] ) . ' ';
				
			else
			{
				$where = '';
				
				foreach( $options[ 'where' ] as $req  )
					$where[] = $this->miqro->escape( $req );
				
				$sql .= implode( ' AND ', $where ) . ' ';
			}
		}
		
		$builder = new MiqroBuilder( $this->miqro, $sql );
		$builder->set( 'table', $this->tablename )->set( 'data', implode( ', ', $sqldata ) );
		
		$this->miqro->mysqli->query( $builder );
		
		if( !empty( $this->miqro->mysqli->error ) )
			MiqroDB::$lastError = $this->miqro->mysqli->error;
	}
	
	/**
	 * Select a table from the database
	 *
	 * @param $fields string|array The fields to select as array or string (or * to select all fields)
	 * @param $options array The options for the select query.
	 * Available options:
	 * - where
	 * - 
	 */
	public function select( $fields, $options = array() )
	{
		if( !is_array( $options ) )
			throw new MiqroException( 'Options parameters not an array', 3 );
		
		if( strpos( $fields, ',' ) !== false )
			$fields = explode( ',', $fields );
		
		if( !is_array($fields) && trim( $fields ) !== '*' )
			$sql = 'SELECT `$fields` FROM `$table` ';
		else
			$sql = 'SELECT * FROM `$table` ';
	
		if( isset ( $options[ 'where' ] ) )
		{
			$sql .= 'WHERE ';
			if( !is_array( $options[ 'where' ] ) )
				$sql .= $options[ 'where' ] . ' ';
				
			else
			{
				$where = '';
				
				foreach( $options[ 'where' ] as $req  )
					$where[] = $req;
				
				$sql .= implode( ' AND ', $where ) . ' ';
			}
		}
		
		if( isset( $options[ 'order' ] ) )
			$sql .= 'ORDER BY ' . $this->miqro->escape( $options[ 'order' ] ) . ' ';
		
		if( isset( $options[ 'limit' ] ) )
			$sql .= 'LIMIT ' . $options[ 'limit' ] . ' ';
		
		$builder = new MiqroBuilder( $this->miqro, $sql );
		$builder->set( 'fields', ( is_array( $fields ) ? implode( ',', $fields ) : $fields ) );
		$builder->set( 'table', $this->tablename );
		
		$query = $this->miqro->mysqli->query( $builder );
		
		if( !empty( $this->miqro->mysqli->error ) )
			MiqroDB::$lastError = $this->miqro->mysqli->error;
		
		return new MiqroSelectResult( $this, $query );
	}
	
	/**
	 * Insert a new row in the table
	 * 
	 * @param param assoc: The field>value data. Structure:
	 *   'field' => 'row'
	 */
	public function insert( $param )
	{
		if( !is_array( $param ) )
			throw new MiqroException( 'Insert parameters not an array', 3 );
		
		$sql = 'INSERT INTO `$table` (';
		
		$fields = '';
		foreach( $param as $key => $value )
		{
			$fields .= ( !empty( $fields ) ? ', `' : '`' ) . $this->miqro->escape( $key ) . '`';
		}
		
		$sql .= $fields . ') VALUES (';
		
		$values = '';
		foreach( $param as $key => $value )
		{
			$values .= ( !empty( $values ) ? ', \'' : '\'' ) . $this->miqro->escape( $value ) . '\'';
		}
		
		$sql .= $values . ');';
		$this->miqro->mysqli->query( (new MiqroBuilder( $this->miqro, $sql ))->set( 'table', $this->tablename, true ) );
		
		if( !empty( $this->miqro->mysqli->error ) )
			MiqroDB::$lastError = $this->miqro->mysqli->error;
	}
	
	
	public function getKey( $key = 'PRIMARY' )
	{
		return $this->miqro->mysqli->query( 'SHOW INDEX FROM ' . $this->tablename . ' WHERE Key_name = \'' . $this->miqro->escape( $key ) . '\'' )->fetch_object()->Column_name;
	}
}

abstract class MiqroResult
{
	
	protected $table;
	protected $query;
	protected $entries = array();
	
	final public function __construct( $table, $query )
	{
		
		if( $query == null )
			throw new MiqroException( 'The query result is null', 4 );
		
		if( $table == null )
			throw new MiqroException( 'The table is null', 6 );
		
		$this->table = $table;
		$this->query = $query;
		
		$this->handle();		
	}
	
	abstract protected function handle();
	
	final public function getEntry( $num )
	{
		return new MiqroEntry( $this->entries[ $num ] );
	}
	
	final public function getRowObject( $num )
	{
		return new MiqroRowObject( $this->table, $this->entries[ $num ] );
	}
	
	final public function size()
	{
		return count( $this->entries );
	}
	
	final public function getEntries()
	{
		return $this->entries;
	}
	
	final public function getAll( $key, $value )
	{
		$output = array();
		foreach( $this->entries as $entry )
		{
			$output[ $entry[ $key ] ] = $entry[ $value ];
		}
		
		return $output;
	}
	
	final public function getAssoc( $key )
	{
		$output = array();
		foreach( $this->entries as $entry )
		{
			$output[ $entry[ $key ] ] = $entry;
		}
		return $output;
	}
	
}

class MiqroSelectResult extends MiqroResult
{
	
	protected function handle()
	{
		while( $row = $this->query->fetch_array() )
		{
			$this->entries[] = $row;
		}
	}
	
}

class MiqroEntry
{
	
	protected $fields = array();
	
	public function __construct( $row )
	{
		$this->fields = $row;
	}
	
	public function getField( $fieldname )
	{
		return $this->fields[ $fieldname ];
	}
	
	public function getFields()
	{
		return $this->fields;
	}
	
	public function __toString()
	{
		$output = '';
		foreach( $this->fields as $key => $value )
		{
			$output .= ( !empty( $output ) ? PHP_EOL : '' ) . $key . ' => ' . $value;
		}
		return $output;
	}
	
}

class MiqroRowObject
{
	private $options = array();
	private $originalIdentifier;
	private $table;
	private $unique;
	
	public function __construct( $table, $row )
	{
		$this->table = $table;
		$this->options = $row;
		$this->originalIdentifier = $row[ $table->getKey() ];
	}
	
	public function __get( $key )
	{
		return ( $this->__isset( $key ) ? $this->options[ $key ] : null );
	}
	
	public function __set( $key, $value )
	{
		$this->options[ $key ] = $value;
	}
	
	public function __isset( $key )
	{
		return !empty( $this->options[ $key ] );
	}
	
	public function __destruct()
	{
		$this->table->update( $options, array( 'where' => $this->table->getKey() . ' = ' . $this->originalIdentifier ) );
	}

}

class MiqroException extends Exception
{
	
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		if( MiqroDB::$debug )
			echo '<strong>MiqroDB SQL Error:</strong> ' . MiqroDB::$lastError . '<br />';
		
		parent::__construct($message, $code, $previous);
	}
	
	public function __toString() {
        return __CLASS__ . "#{$this->code} ({$this->message})";
    }
	
}

class MiqroBuilder
{

	private $miqro;
	private $query;
	
	public function __construct( $miqro, $query )
	{
		$this->miqro = $miqro;
		$this->query = $query;
	}
	
	public function set( $key, $value, $escape = false )
	{
		$this->query = str_replace( '$' . $key, ( $escape ? $this->miqro->escape( $value ) : $value ), $this->query );
		return $this;
	}
	
	public function __toString()
	{
		return $this->query;
	}

}
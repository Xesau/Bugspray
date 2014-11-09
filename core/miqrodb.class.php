<?php

/**
 * MiqroDB -- The simple MySQLi interface
 *
 * @version     1.3
 * @author      Xesau
 * @url         http://code.xesau.eu/
 */
class MiqroDB
{
    
    public $mysqli;
    public static $debug = false;
    public static $lastError = '';
    private $lastQueries = [];
    
    public function query( $sql )
    {
        $this->lastQueries[] = $sql;
        return $this->mysqli->query( $sql );
    }
    
    public function __construct( $con )
    {
        if( $con == null )
            throw new MiqroException( 'Passed MySQLi object is null', 0 );
        
        elseif( $con->connect_error )
            throw new MiqroException( 'Passed MySQLi object is invalid', 1 );
        
        else
            $this->mysqli = $con;
    }
    
    public function createTable( $name, $fields, $options = [] )
    {
        if( !is_array( $options ) )
            throw new MiqroException( 'Parameter $options isn\'t an array', 3 );
        
        if( !is_array( $fields ) )
            throw new MiqroException( 'Parameter $fields isn\'t an array', 3 );
        
        $fieldDefault = [ 
            'type' => 'int',  
            'length' => '11',  
            'data' => null,  
            'null' => false,  
            'primary' => false,  
            'unique' => false,
            'default' => null,
            'comment' => null,
            'autoIncrement' => false,
            'fulltext' => false
        ];
        
        $optionsDefault = [
            'engine' => 'InnoDB',
            'ifNotExists' => false,
            'like' => null,
            'dropIfExists' => false
        ];
        
        $options = array_merge( $optionsDefault, $options );
        
        if( $options[ 'dropIfExists' ] == true ) ( new MiqroBuilder( $this, 'DROP TABLE IF EXISTS $table' ) )->set( 'table', $name )->execute();
        
        $builder = new MiqroBuilder( $this, 'CREATE TABLE $ifNotExists $name $like ( $fields $keys ) ENGINE = $engine' );
        $builder->set( 'name', $name );
        
        $builder->set( 'ifNotExists', $options[ 'ifNotExists' ] == true ? 'IF NOT EXISTS' : '' );
        $builder->set( 'like', ( $options[ 'like' ] != null ? $options[ 'like' ] : '' ) );
        $builder->set( 'engine', $options[ 'engine' ] );
        
        $fieldSQLs = [];
        $primaries = [];
        $uniques = [];
        $fulltexts = [];
        
        foreach( $fields as $key => $value )
        {
            $value = array_merge( $fieldDefault, $value );
            
            $fieldBuilder = new MiqroBuilder( $this, '$name $type$data $null $default $autoIncrement $comment' );
            
            $fieldBuilder->set( 'name', $key );
            $fieldBuilder->set( 'type', strtoupper( $value[ 'type' ] ) );
            $fieldBuilder->set( 'data', $value[ 'length' ] !== null ? ( '(' . ( $value[ 'data' ] == null ? $value[ 'length' ] : $value[ 'data' ] ) . ')' ) : '' );
            $fieldBuilder->set( 'null', $value[ 'null' ] == true ? 'NULL' : 'NOT NULL' );
            $fieldBuilder->set( 'default', $value[ 'default' ] != null ? 'DEFAULT \'' . $this->escape( $value[ 'default' ] ) . '\'' : '' );
            $fieldBuilder->set( 'autoIncrement', $value[ 'autoIncrement' ] == true ? 'AUTO_INCREMENT' : '' );
            $fieldBuilder->set( 'comment', $value[ 'comment' ] );
            
            if( $value[ 'primary' ] == true ) $primaries[] = $key;
            if( $value[ 'unique' ] == true ) $uniques[] = $key;
            if( $value[ 'fulltext' ] == true ) $fulltexts[] = $key;
            
            $fieldSQLs[] = $fieldBuilder->__toString();
        }
        
        $builder->set( 'fields', implode( ', ', $fieldSQLs ) );
        
        $primkeysqls = [];
        $fultkeysqls = [];
        
        foreach( $primaries as $prim )
            $primkeysqls[] = 'PRIMARY KEY (' . $prim . ')';
        
        foreach( $fulltexts as $fult )
            $fultkeysqls[] = 'FULLTEXT KEY ' . $fult . ' (' . $fult . ')';
        
        $builder->set( 'keys',
            ( !empty( $primkeysqls )
                ? ', ' . implode( ', ', $primkeysqls ) : '' ) . 
            ( !empty( $uniques )
                ? ', ' . 'UNIQUE KEY(' . implode( ', ', $uniques ) . ')' : '' ) . 
            ( !empty( $fultkeysqls )
                ? ', ' . implode( ', ', $fultkeysqls ) : '' ) );
        
        
        $builder->execute();
    }
    
    public function table( $tablename )
    {
        $query = $this->query( 'SHOW TABLES LIKE \'' . $this->escape( $tablename ) . '\'' );
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
        $query = $this->query( 'SHOW TABLES' );
        if( $query !== null )
            while( $table = $query->fetch_array() )
                $tables[] = $table[0];
        
        return $tables;
    }
    
    public function getLastQueries()
    {
        return $this->lastQueries;
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
     * Remove data
     */
    public function delete( $options = [] )
    {
        if( !is_array( $options ) )
            throw new MiqroException( 'Parameter $options isn\'t an array', 3 );
        
        $builder = new MiqroBuilder( $this->miqro, 'DELETE FROM $table ' );
        $builder->set( 'table', $this->tablename );
        
        if( !empty( $options[ 'where' ] ) )
        {
            $builder->add( 'WHERE $whereData ' );
            $builder->set( 'whereData', ( is_array( $options[ 'where' ] ) ? implode( ' AND ', $options[ 'where' ] ) : $options[ 'where' ] ) );
        }
                          
        if( !empty( $options[ 'limit' ] ) )
        {
            $builder->add( 'LIMIT $limit' );
            $builder->set( 'limit', $options[ 'limit' ] );
        }
                          
        $builder->execute();
    }
    
    /**
     * Update rows in the table
     * 
     * @param $update array The update data. Structure:
     * 'field' => 'value'
     * 
     */
    public function update( $update, $options = [] )
    {
        if( !is_array( $options ) )
            throw new MiqroException( 'Options parameters not an array', 3 ); 
            
        $sql = 'UPDATE $table SET $data ';
        
        $sqldata = [];
        
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
        
        $builder->execute();
        
        if( !empty( $this->miqro->mysqli->error ) )
            MiqroDB::$lastError = $this->miqro->mysqli->error;
    }
    
    public function updateWhere( $fields, $update, $where )
    {
        if( !is_array( $fields ) )
            throw new MiqroException( 'Parameter $fields not an array', 3 );
        
        foreach( $fields as $key => $value )
        {
            $builder = new MiqroBuilder( $this->miqro, 'UPDATE $table SET `$update` = \'$value\' WHERE $where = \'$key\'' );
            $builder->set( 'table', $this->tablename )->set( 'where', $where )->set( 'update', $update );
            $builder->set( 'key', $key )->set( 'value', $value );

            $builder->execute();
        }
    }
    
    /**
     * Update a row in the table and set field data to $field
     * 
     * @param fields array The fields, with this structure:
     *    'field' => 'new value',
     *    'field2' => 'new value2',
     *    etc
     * @param options array The options parameter, for the WHERE clause
     */
    public function updateFields( $fields = [], $options = [] )
    {
        if( !is_array( $options ) )
            throw new MiqroException( 'Parameter $options not an array', 3 ); 
        
        if( !is_array( $options ) )
            throw new MiqroException( 'Parameter $fields not an array', 3 ); 
        
        $builder = new MiqroBuilder( $this->miqro, 'UPDATE $table SET $updateData ' );
        
        $update = '';
        foreach( $fields as $key => $value )
        {
            if( !$update == '' ) $update .= ', ';
            $update .= '`' . $key . '` = \'' . $value . '\'';
        }
        
        $builder->set( 'table', $this->tablename )->set( 'updateData', $update );
        
        if( !empty ( $options[ 'where' ] ) )
        {
            $builder->add( 'WHERE $whereData' );
            if( !is_array( $options[ 'where' ] ) )
                $builder->set( 'whereData', $options[ 'where' ] );                
            else
                $builder->set( 'whereData', implode( ' AND ', $where ) );
        }
        
        $builder->execute();
    }
    
    /**
     * Select a table from the database
     *
     * @param $fields string|array The fields to select as array or string (or * to select all fields)
     * @param $options array The options for the select query.
     * Available options:
     * - where
     * - order
     * - limit
     */
    public function select( $fields, $options = array() )
    {
        if( !is_array( $options ) )
            throw new MiqroException( 'Options parameters not an array', 3 );
        
        if( strpos( $fields, ',' ) !== false )
            $fields = explode( ',', $fields );
        
        if( !is_array($fields) && trim( $fields ) !== '*' )
            $sql = 'SELECT $fields FROM $table ';
        else
            $sql = 'SELECT * FROM $table ';
    
        if( isset ( $options[ 'where' ] ) )
        {
            $sql .= 'WHERE ';
            if( !is_array( $options[ 'where' ] ) )
                $sql .= $options[ 'where' ] . ' ';
                
            else
            {
                $where = [];
                
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

        $query = $builder->execute();
        
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
        
        $sql = 'INSERT INTO $table (';
        
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
        $this->miqro->query( (new MiqroBuilder( $this->miqro, $sql ))->set( 'table', $this->tablename, true ) );
        
        if( !empty( $this->miqro->mysqli->error ) )
            MiqroDB::$lastError = $this->miqro->mysqli->error;
    }
    
    /**
     * Insert a many rows into the table
     * 
     * @param entries assoc: The field>value data. Structure:
     *   [ 'field' => 'row' ],
     *   [ 'field' => 'row' ],
     *   [ 'field' => 'row' ]
     */
    public function insertMany( $entries, $options = [ 'existsKey' => null ] )
    {
        if( !is_array( $entries ) )
            throw new MiqroException( 'Insert parameters not an array', 3 );
        
        if( !is_array( $options ) )
            throw new MiqroException( 'Options parameter not an array', 3 );

        ## ESCAPE ALL FIELD VALUES
        foreach( $entries as $entry ) foreach( $entry as $key => $value) $entry[ $key ] = $this->miqro->escape( $value );
        
        ## EVERY ENTRY
        foreach( $entries as $entry)
        {
              if( $options[ 'existsKey' ] == null ||
               (    $options[ 'existsKey' ] !== null &&
                    $this->select( $options[ 'existsKey' ], [ 'where' => $options[ 'existsKey' ] . ' = \'' . $entry[ $options[ 'existsKey' ] ] . '\'' ] )->size() == 0 ) )
                $builder = ( new MiqroBuilder( $this->miqro, 'INSERT INTO $table (`' . implode( '`, `', array_keys( $entry ) ) . '`) VALUES (\'' . implode( '\', \'', array_values( $entry ) ) . '\')' ) )->set( 'table', $this->tablename )->execute();
        }
    }
    
    /**
     * Count the amount of entries in the table
     * 
     * @param options array Options (where) 
     * @since 1.3
     */
    public function count( $options = [] )
    {
        $builder = new MiqroBuilder( $this->miqro, 'SELECT COUNT(*) FROM $table' );
        $builder->set( 'table', $this->tablename );
        
        if( !empty( $options[ 'where' ] ) )
        {
            if( !is_array( $options[ 'where' ] ) )
               $builder->set( 'whereData', $options[ 'where' ] );
            else
               $builder->set( 'whereData', implode( ' AND ', $options[ 'where' ] ) );
            
            $builder->add( ' WHERE $whereData' );
        }
        
        $query = $builder->execute();
        $array = $query->fetch_array();
        return $array[0];
    }
    
    public function getKey( $key = 'PRIMARY' )
    {
        return $this->miqro->query( 'SHOW INDEX FROM ' . $this->tablename . ' WHERE Key_name = \'' . $this->miqro->escape( $key ) . '\'' )->fetch_object()->Column_name;
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
    private $set = [];
    
    public function __construct( $miqro, $query )
    {
        $this->miqro = $miqro;
        $this->query = $query;
    }
    
    public function set( $key, $value, $escape = false )
    {
        $this->set[ $key ] = [ $value, $escape ];
        return $this;
    }
    
    public function add( $sql )
    {
        $this->query .= $sql;
    }
    
    public function __toString()
    {
        $tmp = $this->query;
        foreach( $this->set as $key => $value ) 
        {   $tmp = str_replace( '$' . $key, $value[1] ? $this->miqro->escape( $value[0] ) : $value[0], $tmp );
        }
        return $tmp;
    }
    
    public function execute()
    {
        $query = $this->miqro->query( $this->__toString() );
        if( MiqroDB::$debug == true ) echo $this->miqro->mysqli->error;
        return $query;
    }

}

<?php

class DB
{
    private static $i;
    
    public static function loaderInit( $inst )
    {
        if( self::$i == null ) self::$i = $inst;
    }
    
    public static function table( $tableName )
    {
        debug_backtrace();
        return self::$i->table( $tableName );
    }
    
    public static function escape( $data )
    {
        debug_backtrace();
        return self::$i->escape( $data );
    }
    
    public static function lastQueries()
    {
        return self::$i->getLastQueries();
    }
    
    public static function mysqli() 
    {
        return self::$i->mysqli;
    }
    
}

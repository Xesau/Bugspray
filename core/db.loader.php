<?php

class DB
{
    public static $i;
    
    public static function loaderInit( $inst )
    {
        if( self::$i == null ) self::$i = $inst;
    }
}

class SingletonWrapper
{
    
    public static $__inst = null;
    
    public static function loaderInit( $inst )
    {
        if( self::$__inst == null ) self::$__inst = $inst;
    }
    
    public function __call( $name, $args )
    {
        eval( 'return self::$__inst->' . $name . '(\'' . implode( '\', \'', $args ) . '\');' );
    }
    
    public static function __callStatic( $name, $args )
    {
        echo 'return self::$__inst->' . $name . '(\'' . implode( '\', \'', $args ) . '\');';
        eval( 'return self::$__inst->' . $name . '(\'' . implode( '\', \'', $args ) . '\');' );
    }
    
    public function __get( $var )
    {
        return self::$__inst->$var;
    }
    
    public function __set( $var, $value )
    {
        self::$__inst->$var = $value;
    }
    
}
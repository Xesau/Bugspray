<?php

/*-----------  BUGSPRAY  Bug tracking software -----------*\
 * http://bugspray.xesaueu/
 * 
 * 
 *--------------------------------------------------------*/

class Settings
{

    private static $bufferedSettings;

    public function init( $settings = [] )
    {
        if( is_array( $settings ) )
            if( !empty( $settings ) )
                self::$bufferedSettings = $settings;
            else
                self::$bufferedSettings = DB::table( prefix( 'settings' ) )->select( '*' )->getAll( 'setting', 'value' );
        else
            throw InvalidArgumentException( 'Parameter $settings in <code>Settings::init( array $settings = [] )</code> is not an array.' ) ;
    }
    
    public function get( $setting )
    {
        if( is_array( self::$bufferedSettings ) )
        {
            if( array_key_exists( $setting, self::$bufferedSettings ) )
                return self::$bufferedSettings[ $settign ];
            else
            {
                $settingValue = DB::table( prefix( 'settings' ) )->select( 'value', [ 'where' => 'setting = \'' . DB::escape( $setting ) . '\'' ] )->getEntry( 0 )->getField( 'value' );
                self::$bufferedSettings[ $setting ] = $settingValue;
                return $settingValue;
            }
        }
        else
            throw new InvalidArgumentException( 'The <code>Settings</code> class has not yet been initialized. Use <code>Settings::init()</code> first.' );
    }
    
    public function set( $setting, $value )
    {
        if( is_array( self::$bufferedSettings ) )
        {
            self::$bufferedSettings[ $setting ] = DB::escape( $value );
            if( array_key_exists( $setting, self::$bufferedSettings ) )
                DB::table( prefix( 'settings' ) )->updateFields( [ 'value' => $value ], [ 'where' => 'setting = \'' . DB::escape( $setting ) . '\'' ] );
            else
                DB::table( prefix( 'settings' ) )->insert( [ 'setting' => $setting, 'value' => $value ] );
        }
        else
            throw new InvalidArgumentException( 'The <code>Settings</code> class has not yet been initialized. Use <code>Settings::init()</code> first.' );
    }
    
    public function all()
    {
        return self::$bufferedSettings;
    }

}
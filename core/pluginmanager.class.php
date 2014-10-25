<?php

class PluginManager
{
    private static $plugins = [];
    private static $adminpages = [];
    private static $pages = [];
    
    /**
     * Register a new plugin and copy it's language data
     * 
     * @param plugin Plugin The plugin to register
     */
    public static function registerPlugin( Plugin &$plugin )
    {
        # Check wether the plugin isn't loaded already
        if( !in_array( $plugin, self::$plugins ) )
            self::$plugins[] = $plugin;
        
        # Add additional language keys and values
        global $l;
        if( is_array( $plugin->getLanguageAddons() ) )
            $l = array_merge( $l, $plugin->getLanguageAddons() );
    }
    
    /**
     * Get all the registered plugins
     * 
     * @return array The plugins
     */
    public static function getPlugins()
    {
        return self::$plugins;
    }
    
    /**
     * Register a new admin page
     * 
     * @param name string The name of the page
     * @param plugin Plugin The plugin that owns the page
     */
    public static function registerAdminPage( $name, Plugin $plugin )
    {
        if( array_key_exists( $name, self::$adminpages ) )
            self::$adminpages[ $name ] = $plugin;
    }
    
    /**
     * Register a new page
     * 
     * @param name string The name of the page
     * @param plugin Plugin The plugin that owns the page
     */
    public function reigsterPage( $name, Plugin $plugin )
    {
        if( self::$pages[ $name ] == null )
            self::$pages[ $name ] = $plugin;
    }
    
    /**
     * Get the by plugins registered admin pages
     * 
     * @return array The pages
     */
    public static function getAdminPages()
    {
        return self::$adminpages;
    }
    
    /**
     * Get the by plugins registered pages
     * 
     * @return array The pages
     */
    public static function getPages()
    {
        return self::$pages;
    }
    
    public static function hasAdminPage( $page )
    {
        return in_array( $page, self::$adminpages );
    }
    
    public static function hasPage( $page )
    {
        return in_array( $page, self::$pages );
    }
    
}

abstract class Plugin
{
    
    abstract public function getName();
    abstract public function getVersion();
    
    public function getAuthor() { return ''; }
    public function getWebsite() { return ''; }
    
    public function getLanguageAddons()
    {
        return [];
    }

}

?>
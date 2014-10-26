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
     * @param pagedata PageData the page data object
     * @param plugin Plugin The plugin that owns the page
     */
    public static function registerAdminPage( $name, $pagedata, Plugin $plugin )
    {
        if( !array_key_exists( $name, self::$adminpages ) )
            self::$adminpages[ $name ] = $pagedata->setPlugin( $plugin );
    }
    
    /**
     * Register a new page
     * 
     * @param name string The name of the page
     * @param pagedata PageData the page data object
     * @param plugin Plugin The plugin that owns the page
     */
    public function reigsterPage( $name, $pagedata, Plugin $plugin )
    {
        if( !self::$pages[ $name ] == null )
            self::$pages[ $name ] = $pagedata->setPlugin( $plugin );
    }
    
    /**
     * Get the by plugins registered admin pages
     * 
     * @return array The pages
     */
    public static function getAdminPages( $asArray = false )
    {
        if( !$asArray ) return self::$adminpages;
        
        foreach( self::$adminpages as $page => $data ) $pages[ $page ] = $data->toArray();
        return $pages != null ? $pages : [];
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
        return array_key_exists( $page, self::$adminpages );
    }
    
    public static function hasPage( $page )
    {
        return array_key_exists( $page, self::$pages );
    }
    
    public static function getPage( $page )
    {
        return ( self::hasPage( $page ) ? self::$pages[ $page ] : null );
    }
    
    public static function getAdminPage( $page )
    {
        return ( self::hasAdminPage( $page ) ?  self::$adminpages[ $page ] : null );
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
    
    final public function toArray()
    {
        return [
            'name' => $this->getName(),
            'version' => $this->getVersion(),
            'author' => $this->getAuthor(),
            'website' => $this->getWebsite(),
        ];
    }

}

?>
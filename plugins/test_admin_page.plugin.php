<?php


class TestPlugin extends Plugin
{

    public function getName()
    {
        return 'TestPlugin';
    }
    
    public function getAuthor()
    {
        return 'Xesau';
    }
    
    public function getVersion()
    {
        return '1.0';
    }
    
    public function getWebsite()
    {
        return 'http://www.xesau.eu/';
    }
    
    public function __construct()
    {
        PluginManager::registerAdminPage( 'test', $this );   
    }
    
    public function getLanguageAddons()
    {
        return [ 'test_addon' => 'Test Addon' ];
    }

}

PluginManager::registerPlugin( new TestPlugin );
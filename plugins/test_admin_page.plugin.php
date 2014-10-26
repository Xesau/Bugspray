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
        global $l;
        PluginManager::registerAdminPage( 'test', ( new PageData )->setTitle( 'Test Page' )->setTemplate( 'test' )->setGlyph( 'file' ), $this );   
    }
    
    public function getLanguageAddons()
    {
        if( setting( 'language' ) == 'dutch' )
            return [ 'test_addon'  => 'Testtoevoeging', 'test_page' => 'Testpagina' ];
        return [ 'test_addon' => 'Test Addon', 'test_page' => 'Test Page' ];
    }

}

PluginManager::registerPlugin( new TestPlugin );
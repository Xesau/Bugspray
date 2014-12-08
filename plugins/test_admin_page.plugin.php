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
    
    public function getLanguageAddons($page = null)
    {
        if( setting( 'language' ) == 'dutch' )
            return [
                'test_addon'  => 'Testinvoegtoepassing',
                'test_page' => 'Testpagina',
                'test_page_of_test_addon' => 'Dit is een testpagina van een testinvoegtoepassing'
            ];
        return [
            'test_addon' => 'Test Addon',
            'test_page' => 'Test Page',
            'test_page_of_test_addon' => 'This is a test page of a test addon'
        ];
    }
    
    public function getTemplateVariables()
    {
        if( !empty( $_GET[ 'id' ] ) && $_GET[ 'id' ] == 'test' && IN_ADMIN )
            return [
                'custom_variable' => 'Custom Variable (value)'
            ];
        else
            return [];
    }

}

PluginManager::registerPlugin( new TestPlugin );
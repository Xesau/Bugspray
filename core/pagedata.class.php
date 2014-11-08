<?php

class PageData
{

	private $template = '';
	private $title = '';
    private $plugin = null;
	private $glyph = '';
    private $css = [];
    private $js = [];
    
    public function __construct( Plugin $plugin = null )
    {
        if( $plugin !== null )
            $this->plugin = $plugin;
    }
    
	public function setTemplate( $template )
	{
		$this->template = $template;
		return $this;
	}
	
	public function setTitle( $title )
	{
		$this->title = $title;
		return $this;
	}
    
    public function setGlyph( $glyph )
    {
        $this->glyph = $glyph;
        return $this;
    }
    
    public function setPlugin( Plugin $plugin )
    {
        $this->plugin = $plugin;
        return $this;
    }
    
    public function getPlugin()
    {
        return $this->plugin;
    }
	
	public function toArray()
	{
		return array(
			'template' => $this->template,
			'title' => $this->title,
            'plugin' => ( $this->plugin !== null ? $this->plugin->toArray() : '' ),
            'glyph' => $this->glyph,
            'js' => $this->js,
            'css' => $this->css
		);
	}
    
    public function addJS( $js )
    {
        if( !in_array( $js, $this->js ) )
            $this->js[] = $js;
        return $this;
    }
    
    public function addCSS( $css )
    {
        if( !in_array( $css, $this->css ) )
            $this->css[] = $css;
        return $this;
    }
    
    public function getJS()
    {
        return $this->js;
    }
    
    public function getCSS()
    {
        return $this->css;
    }

}
<?php

class PageData
{

	private $template = '';
	private $title = '';
    private $plugin = '';
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
        $this->glyph = $gylph;
    }
	
	public function toArray()
	{
		return array(
			'template' => $this->template,
			'title' => $this->title,
            'plugin' => $this->plugin,
            'glyph' => $this->glyph,
            'js' => $this->js,
            'css' => $this->css
		);
	}
    
    public function addJS( $js )
    {
        if( !in_array( $js, $this->js ) )
            $this->js[] = $js;
    }
    
    public function addCSS( $css )
    {
        if( !in_array( $css, $this->css ) )
            $this->css[] = $css;
    }

}
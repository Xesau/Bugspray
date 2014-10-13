<?php

class PageData
{

	private $template = '';
	private $title = '';
	
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
	
	public function toArray()
	{
		return array(
			'template' => $this->template,
			'title' => $this->title
		);
	}

}
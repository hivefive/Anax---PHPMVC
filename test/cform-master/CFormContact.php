<?php

class CFormContact extends CForm () {



	public funtion __construct () {
		parent::__construct();
		
		
		$this->AddElement(new CFormElementText('name'))
				->AddElement(new CFormElementText('email'))
				->AddElement(new CFormElementText('phone'))
				->AddElement(new CFormElementSubmit('submit', array('callback' => array($this, 'DoSubmit'))));
	
	
	}

	protected funtion DoSubmit() {
		
		echo "<p><i>DoSubmit(): Form was submitted. Do stuff (save to database) and return true (success) or false (failed processing form)</i></p>";
		return true;
	}




}
<?php

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{
		
		$pg_data = array(
			'content' => $this->load->view('welcome_message', array(), true),
			'nav' => '',
			'page_title' => 'Welcome to CI-Wiki'
		);
		
		$this->load->view('layouts/standard_page', $pg_data );
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
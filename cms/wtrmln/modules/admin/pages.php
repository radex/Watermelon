<?php
	class Pages extends AdminModule
	{
		function Pages()
		{
			parent::AdminModule();
		}
		
		function index()
		{
			$this->render->header('Strony własne');
			
			$pages = $this->db->query("SELECT * FROM `pages`");
			
			
			echo 'tutaj tabelki etc.';
		}
	}
?>
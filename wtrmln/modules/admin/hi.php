<?php
	class Hi extends AdminModule
	{
		function Hi()
		{
			parent::AdminModule();
		}
		
		function index()
		{
			$this->render->header('Panel Admina');
			echo 'siema, elo! <a href="$/pages">test</a>';
			
			$tabelka =
				'==NAZWA TABELI==
				{:PP=50px:DP=100px:TP:CP=20%:}
				[{$(PP)|$(PD)|$(PT)|$(PC)}]
				{$(DP)=25%|$(DD)|$(DT)=255%}';
			
			echo $this->render->paTable($tabelka, array(
			   'pp' => 'KOM1',
			   'pd' => 'KOM2',
			   'pt' => 'KOM3',
			   'pc' => 'KOM4',
			   'dp' => 'KOMA',
			   'dd' => 'KOMB',
			   'dt' => 'KOMC', ));
		}
	}
?>
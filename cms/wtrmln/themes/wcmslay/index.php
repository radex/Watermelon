<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
         "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<?php echo $meta ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo WTRMLN_THEMEURL ?>style.css" media="screen">
</head>
<body>
	<div id="container">
	   <div id="header">
	      <div id="ha"><?php echo $siteheader ?></div>
	      <div id="hs"><?php echo $siteslogan ?></div>
      </div>
      <div id="sidebar">
         <?php echo $menu; ?>
		</div>
		<div id="content">
			<h1><?php echo (defined('WTRMLN_H1') ? WTRMLN_H1 : '&nbsp;'); ?></h1>
			<?php echo $_w_content; ?>
		</div>
		<div id="bottom-bg"></div>
	</div>
	<div id="footer">
      &copy; Copyright 2008 by twoje Imię i Nazwisko<br>
      powered by <a href="http://watermeloncms.sourceforge.net">Watermelon CMS</a><br>
      <br>
      Zapytań do bazy danych: <?php echo DB::queries(); ?><br>
      Wygenerowano w: <?php global $_w_startTime; echo microtime() - $_w_startTime; ?> s
	</div>
</body>
</html>

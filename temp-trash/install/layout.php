<?php
   $content = ob_get_contents();
   ob_end_clean();
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Instalacja Watermelon CMS</title>
   <meta http-equiv="Pragma" content="no-cache, private">
   <meta http-equiv="Expires" content="0">
   <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
   <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
   <meta name="robots" content="noindex,nofollow">
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
   <div id="header">
      <h1><span>Watermelon CMS</span></h1>
   </div>
   <div id="container">
<?php
   $content = explode("\n", $content);
   
   foreach($content as &$var)
   {
      $var = '      ' . $var;
   }
   
   $content = implode("\n", $content);
   echo $content;
?>
   </div>
   <div id="footer">
      &copy; Copyright 2008 <a href="http://radex.i15.eu">Watermelon CMS Team</a>
   </div>
</body>
</html>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
         "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="<?php echo WM_THEMEURL ?>style.css">
</head>
<body>
   <div id="container">
      <div id="header">
         <div id="ha">Watermelon CMS</div>
         <div id="hs">…bo arbuzy dobre są…</div>
         <ul>
            <li><a>Top</a><li><a>Menu</a>
         </ul>
      </div>
      <div id="sidebar">
         Sidebar
      </div>
      <div id="content">
         <?php echo $content; ?>
      </div>
      <div id="bottom-bg"></div>
   </div>
   <div id="footer">
      powered by <strong>Watermelon CMS</strong><br>
      <br>
      <?php if(defined('WM_DEBUG')){ ?>
      Konsola:<br>
      <form method="post" action="http://localhost/w/">
         <textarea name="terminal" style="width:90%; height:40px"></textarea>
         <input type="submit">
      </form>
      <br>---<br>
      <?php if(!empty($_POST['terminal'])) eval($_POST['terminal']) ?>
      <br>---<br>
      Zapytań do bazy danych: <?php echo count(DB::$queriesArray) ?><br>
      Zużyto pamięci: <?php echo memory_get_peak_usage() ?> <?php echo memory_get_usage() ?><br>
      Wygenerowano w: ∞ sekund :><br>
      URL segments:<?var_dump(URI::$segments); ?><br>
      Watermelon segments:<?var_dump(Watermelon::$segments); ?><br>
      Watermelon controller:<?var_dump(Watermelon::$controllerName); ?><br>
      Watermelon module:<?var_dump(Watermelon::$moduleName); ?><br>
      Wykonane zapytania:<br>
      <?php echo '<pre>' . htmlspecialchars(implode("\n", DB::$queriesArray)) . '</pre>'; ?><br>
      Błędy testów jednostkowych:<br>
      <?php UnitTester::printFails(); ?>
      <?php } ?>
   </div>
</body>
</html>

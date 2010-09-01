<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
         "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="<?php echo WM_THEMEURL ?>style.css">
   <?=$this->drawHeadTags()?>
</head>
<body>
   <div id="container">
      <div id="header">
         <div id="ha"><?=$siteName?></div>
         <div id="hs"><?=$siteSlogan?></div>
         <ul>
            <?=$this->drawTextMenu(0)?>
         </ul>
      </div>
      <div id="sidebar">
         <?$this->drawBlockMenu(0)?>
      </div>
      <div id="content">
         <h1><?=$pageTitle?></h1>
         <?=$content?>
      </div>
      <div id="bottom-bg"></div>
   </div>
   <div id="footer">
      <?=$footer?><br>
      powered by <strong>Watermelon CMS</strong><br>
      <br>
      <?php if(defined('WM_Debug')){ ?>
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
      segments:<?var_dump(Watermelon::$segments); ?><br>
      controller:<?var_dump(Watermelon::$controllerName); ?><br>
      module:<?var_dump(Watermelon::$moduleName); ?><br>
      Wykonane zapytania:<br>
      <?php echo '<pre>' . htmlspecialchars(implode("\n", DB::$queriesArray)) . '</pre>'; ?><br>
      Błędy testów jednostkowych:<br>
      <?php UnitTester::printFails(); ?>
      <?php } ?>
   </div>
</body>
</html>

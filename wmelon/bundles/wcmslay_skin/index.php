<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_SkinURL ?>style.css">
<title>Title</title>
<?=$this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?=WM_SiteURL?>"><?=$siteName?></a></div>
      <div id="siteSlogan"><?=$siteSlogan?></div>
   </div>
</header>

<ul id="nav">
   <?=$this->drawTextMenu(0)?>
</ul>
<div id="container">
   <div id="sidebar">
      <?$this->drawBlockMenu(0)?>
   </div>
   <div id="content">
      <?= (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?$this->drawMessages() ?>
      <?=$content?>
   </div>
   <div id="clear"></div>
</div>
<footer>
   <?=$footer?><br>
   powered by <strong>Watermelon CMS</strong><br>
   <br>
   <?php if(defined('WM_Debug')){ ?>
   Zapytań do bazy danych: <?php echo count(DB::$queriesArray) ?><br>
   Zużyto pamięci: <?php echo memory_get_peak_usage() ?> <?php echo memory_get_usage() ?><br>
   Wygenerowano w: <?php echo Benchmark::executionTime() ?> µs<br>
   Wykonane zapytania:<br>
   <?php
   
   echo '<ul style="text-align:left">';
   
   foreach(DB::$queriesArray as $query)
   {
      if(strlen($query) > 150)
      {
         $query = substr($query, 0, 150) . ' (...)';
      }
      
      echo '<li><pre>' . htmlspecialchars($query) . '</pre></li>';
   }
   
   echo '</ul>';
   
   
   /*Błędy testów jednostkowych:
   UnitTester::printFails();*/
   }
   ?>
</footer>
<?=$this->drawTailTags()?>
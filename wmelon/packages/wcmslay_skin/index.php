<!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_SkinURL ?>style.css">
<?=$this->drawHeadTags()?>

<div id="container">
   <header>
      <div id="ha"><?=$siteName?></div>
      <div id="hs"><?=$siteSlogan?></div>
      <ul>
         <?=$this->drawTextMenu(0)?>
      </ul>
   </header>
   <div id="sidebar">
      <?$this->drawBlockMenu(0)?>
   </div>
   <div id="content">
      <?= empty($pageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
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
   Wygenerowano w: ∞ sekund :><br>
   Wykonane zapytania:<br>
   <?php
   
   echo '<ul>';
   
   foreach(DB::$queriesArray as $query)
   {
      if(strlen($query) > 150)
      {
         $query = substr($query, 0, 150) . ' (...)';
      }
      
      echo '<li><pre>' . htmlspecialchars($query) . '</pre></li>';
   }
   
   echo '</ul>';
   
   ?>
   Błędy testów jednostkowych:
   <?php UnitTester::printFails(); ?>
   <?php } ?>
</footer>
<?=$this->drawTailTags()?>
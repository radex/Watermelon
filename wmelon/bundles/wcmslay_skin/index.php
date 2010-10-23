<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_SkinURL ?>style.css">
<title><?= empty($pageTitle) ? $siteName : $pageTitle . ' - ' . $siteName ?></title>
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
   powered by <strong>Watermelon CMS</strong>
   <?php if(defined('WM_Debug')){ ?>
      <br><br>
      Wygenerowano w: <?= round(Benchmark::executionTime(), -2) / 1000 ?> ms<br>
      Peak memory usage: <?= (int) (memory_get_peak_usage() / 1000) ?> KB<br>
      Current memory usage: <?= (int) (memory_get_usage() / 1000) ?> KB<br>
      
      <br>Zapytania wykonane do bazy danych (<?=count(DB::$queriesArray)?>):<br>
      
      <ul style="text-align:left">
      <?php
   
      foreach(DB::$queriesArray as $query)
      {
         if(strlen($query) > 150)
         {
            $query = substr($query, 0, 150) . ' (...)';
         }
      
         echo '<li><pre>' . htmlspecialchars($query) . '</pre></li>';
      }
      ?>
      </ul>
      <?}?>
</footer>
<?=$this->drawTailTags()?>
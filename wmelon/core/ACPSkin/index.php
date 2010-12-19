<?php defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_SkinURL?>style.css">
<?php echo $this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?php echo WM_AdminURL?>"><?php echo $siteName?></a></div>
      <div id="siteSlogan">Panel admina (<a href="<?php echo WM_SiteURL?>">przejdź do strony →</a>)</div>
   </div>
</header>

<nav>
   <ul id="navLeft">
      <?php echo  $this->drawLeftNav() ?>
   </ul>
   <ul id="navRight">
      <?php echo  $this->drawRightNav() ?>
   </ul>
</nav>
<div id="container">
   <?php echo  $this->drawSubNav() ?>
   <div id="content">
      <?php   $this->drawMessages() ?>
      <?php echo  (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?php echo  $content ?>
   </div>
</div>
<footer>
   powered by <strong>Watermelon CMS</strong>
   <?php if(defined('WM_Debug')){ ?>
      <br><br>
      Wygenerowano w: <?php echo  round(Benchmark::executionTime(), -2) / 1000 ?> ms<br>
      Peak memory usage: <?php echo  (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br>
      Current memory usage: <?php echo  (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br>
      
      <br>Zapytania wykonane do bazy danych (<?php echo count(DB::$queriesArray)?>):<br>
      
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
      <?php }?>
</footer>

<?php echo $this->drawTailTags()?>
<script src="<?php echo WM_SkinURL?>ACPSkin.js"></script>
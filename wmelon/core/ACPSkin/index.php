<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?=WM_SkinURL?>style.css">
<?=$this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?=WM_AdminURL?>"><?=$siteName?></a></div>
      <div id="siteSlogan">Panel admina (<a href="<?=WM_SiteURL?>">przejdź do strony →</a>)</div>
   </div>
</header>

<nav>
   <ul id="navLeft">
      <?= $this->drawLeftNav() ?>
   </ul>
   <ul id="navRight">
      <?= $this->drawRightNav() ?>
   </ul>
</nav>
<div id="container">
   <?= $this->drawSubNav() ?>
   <div id="content">
      <?  $this->drawMessages() ?>
      <?= (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?= $content ?>
   </div>
</div>
<footer>
   powered by <strong>Watermelon CMS</strong>
   <?php if(defined('WM_Debug')){ ?>
      <br><br>
      Wygenerowano w: <?= round(Benchmark::executionTime(), -2) / 1000 ?> ms<br>
      Peak memory usage: <?= (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br>
      Current memory usage: <?= (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br>
      
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
<script src="<?=WM_SkinURL?>ACPSkin.js"></script>
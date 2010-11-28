<?defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?=WM_SkinURL?>style.css">
<title><?= empty($pageTitle) ? $siteName : $pageTitle . ' - ' . $siteName ?></title>
<?=$this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?=WM_SiteURL?>"><?=$siteName?></a></div>
      <div id="siteSlogan"><?=$siteSlogan?></div>
   </div>
</header>

<nav>
   <ul id="nav">
      <?=$this->drawTextMenu(0)?>
   </ul>
</nav>
<div id="container">
   <div id="sidebar">
      <?$this->drawBlockMenu(0)?>
   </div>
   <div id="content">
      <?  $this->drawMessages() ?>
      <?= (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?= $content ?>
   </div>
   <div id="clear"></div>
</div>
<footer>
   <?=$footer?><br>
   powered by <strong>Watermelon CMS</strong>
   <?php if(defined('WM_Debug')){ ?>
      <br><br>
      Wygenerowano w: <?= round(Benchmark::executionTime(), -2) / 1000 ?> ms<br>
      Peak memory usage: <?= (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br>
      Current memory usage: <?= (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br>
      
      <br>Zapytania wykonane do bazy danych (<?=count(DB::$queriesArray)?>):<br>
      
      <ul style="text-align:left; font-size:11px">
      <?php
   
      foreach(DB::$queriesArray as $query)
      {
         if(strlen($query) > 165)
         {
            $query = substr($query, 0, 165) . ' (...)';
         }
         
         $query = htmlspecialchars($query);
         
         $query = preg_replace('/(`[^`]+`)/', '<em>$1</em>', $query);
         
         echo '<li><pre style="padding:5px">' . $query . '</pre></li>';
      }
      ?>
      </ul>
      <?}?>
</footer>
<?=$this->drawTailTags()?>
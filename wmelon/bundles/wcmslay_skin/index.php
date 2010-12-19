<?php defined('WM') or die?><!doctype html>
<meta charset="UTF-8">
<link rel="stylesheet" href="<?php echo WM_SystemURL?>core/turbine/css.php?files=wcmslay_skin/style.cssp">
<?php echo $this->drawHeadTags()?>

<header>
   <div>
      <div id="siteName"><a href="<?php echo WM_SiteURL?>"><?php echo $siteName?></a></div>
      <?php  if(!empty($siteSlogan)): ?><div id="siteSlogan"><?php echo $siteSlogan?></div><?php  endif; ?>
   </div>
</header>

<nav>
   <ul id="nav">
      <?php 
      
      echo $this->drawTextMenu(0);
      
      if(Auth::isLogged())
      {   
         echo '<li style="float:right; padding-right:5px"><a href="' . SiteURL('%/') . '">Panel Admina</a></li>';
         echo '<li style="float:right"><a href="' . SiteURL('auth/logout') . '">Wyloguj</a></li>';
      }
      
      ?>
   </ul>
</nav>
<div id="container">
   <?php /*
   <div id="sidebar">
      < ?$this->drawBlockMenu(0)? >
   </div>
   */?>
   <div id="content">
      <?php   $this->drawMessages() ?>
      <?php echo  (empty($pageTitle) || $dontShowPageTitle) ? '' : '<h1>' . $pageTitle . '</h1>' ?>
      <?php echo  $content ?>
   </div>
</div>
<footer>
   <?php echo $footer?><br>
   powered by <strong>Watermelon CMS</strong>
   <?php if(defined('WM_Debug')){ ?>
      <br><br>
      Wygenerowano w: <?php echo  round(Benchmark::executionTime(), -2) / 1000 ?> ms<br>
      Peak memory usage: <?php echo  (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br>
      Current memory usage: <?php echo  (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br>
      
      <br>Zapytania wykonane do bazy danych (<?php echo count(DB::$queriesArray)?>):<br>
      
      <ul style="text-align:left; font-size:11px">
      <?php
   
      foreach(DB::$queriesArray as $query)
      {
         if(strlen($query) > 165)
         {
            $query = substr($query, 0, 165) . ' (...)';
         }
         
         $query = htmlspecialchars($query);
         
         $query = preg_replace('/([A-Z]+)/', '<em>$1</em>', $query);
         
         echo '<li><pre style="padding:5px">' . $query . '</pre></li>';
      }
      ?>
      </ul>
      <?php }?>
</footer>
<?php echo $this->drawTailTags()?>
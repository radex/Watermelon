<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
         "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<?php echo Watermelon::getMeta(); ?>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="<?php echo WM_THEMEURL ?>style.css">
   <script type="text/javascript">
      function msg_hide(height)
      {
         if(height == 0) return;

         document.getElementById('topmsg').style.height = height + 'px';

         setTimeout("msg_hide(" + (height-1) + ")", 5);
      }

      function msg_hide_start()
      {
    	   document.getElementById('topmsg').style.overflow = 'hidden';
         setTimeout("msg_hide(document.getElementById('topmsg').clientHeight)", 5000);
         //msg_hide(document.getElementById('topmsg').clientHeight);
      }
   </script>
</head>
<body onload="msg_hide_start()">
   <!--[if IE 6]>
      <div id="ie_info">
         <div>
            Niestety przeglądarka Internet Explorer 6 nie obsługuje poprawnie panelu admina Watermelon CMS-a.
            Zalecana jest aktualizacja przeglądarki do najnowszej wersji lub użycie alternatywnej przeglądarki
            internetowej (np. Opera, Firefox, Chrome)
         </div>
      </div>
   <![endif]-->
   <div id="container">
      <div id="header">
         <h1><span>Watermelon CMS</span></h1>
         <h2>Panel admina (<a href="<?php echo WM_MAINURL ?>">odwiedź stronę</a>)</h2>
         <ul>
            <?php
            
            $menuContent = getMenu();
            
            echo getEvents();
            
            ?>
            <li><a href="<?php echo WM_MAINURL ?>login/logout">Wyloguj</a></li>
         </ul>
      </div>
      <div id="sidebar">
         
         <a href="<? echo site_url('') ?>">Strona główna</a>
         <hr>
         
         <?php
         
         echo $menuContent;
         
         ?>
         
         <div class="menuedit"><a href="<?php echo site_url('menuedit/pa') ?>">edytuj menu</a></div>
         
         
         
      </div>
      <div id="content">
<?php echo (defined('WM_H1') ? '<h1>' . WM_H1 . '</h1>' : ''); ?>
<div id="topmsg"><?php echo $_w_message; ?></div>
<?php echo $_w_content; ?>
      </div>
   </div>
   <div id="footer">
      <div class="dr">
         wersja 1.0a1
      </div>
      Powered by Watermelon CMS
      <br>
      <? if(defined('DEBUG_FOOTER')){ ?>
      Zapytań do bazy danych: <?php echo DB::queries(); ?><br>
      Cache'owanie widoków <?php echo (defined('CACHE_VIEWS') ? 'włączone' : 'wyłączone') ?><br>
      Zużyto pamięci: <?php echo memory_get_peak_usage() ?> <?php echo memory_get_usage() ?><br>
      Wygenerowano w: <?php global $_w_startTime; Benchmark::$benchmarks['watermelon_time'] = $_w_startTime; echo Benchmark::end('watermelon_time', BENCHMARK_SITE); ?> µs<br>
      <?php if(defined('DEBUG')) echo '---<br><div class="tl"><pre>' . htmlspecialchars(implode("\n", DB::queriesList())) . '</pre></div>'; ?>
      <? } ?>
   </div>
</body>
</html>
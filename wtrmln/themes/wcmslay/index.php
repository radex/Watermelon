<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
         "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<?php echo getMeta(); ?>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="<?php echo WTRMLN_THEMEURL ?>style.css">
   <!--[if lt IE 7]>
   <link rel="stylesheet" href="<?php echo WTRMLN_THEMEURL ?>ie.css">
   <![endif]-->
</head>
<body>
   <div id="container">
      <div id="header">
         <div id="ha"><?php echo $siteheader ?></div>
         <div id="hs"><?php echo $siteslogan ?></div>
         <ul>
            <li class="actual_page"><a href="#aad">Główna</a></li>
            <li><a href="#bd">Inna</a></li>
            <li><a href="#cd">Jeszcze inna</a></li>
         </ul>
      </div>
      <div id="sidebar">
         <?php echo $menu; ?>
      </div>
      <div id="content">
         <?php echo (defined('WTRMLN_H1') ? '<h1>' . WTRMLN_H1 . '</h1>' : ''); ?>
         <?php echo $_w_content; ?>
      </div>
      <div id="bottom-bg"></div>
   </div>
   <div id="footer">
      &copy; Copyright 2008 by twoje Imię i Nazwisko<br>
      powered by <a href="http://watermeloncms.sourceforge.net">Watermelon CMS</a><br>
      <br>
      <?php if(defined('DEBUG_FOOTER')){ ?>
      Zapytań do bazy danych: <?php echo DB::queries(); ?><br>
      Cache'owanie widoków <?php echo (defined('CACHE_VIEWS') ? 'włączone' : 'wyłączone') ?><br>
      Zużyto pamięci: <?php echo memory_get_peak_usage() ?> <?php echo memory_get_usage() ?><br>
      Wygenerowano w: <?php global $_w_startTime; Benchmark::$benchmarks['watermelon_time'] = $_w_startTime; echo Benchmark::end('watermelon_time', BENCHMARK_SITE); ?> µs<br>
      <?php if(defined('DEBUG')) echo '---<br><div class="tl"><pre>' . htmlspecialchars(implode("\n", DB::queriesList())) . '</pre></div>'; ?>
      <?php } ?>
   </div>
</body>
</html>

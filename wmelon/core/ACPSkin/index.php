<?php die?>
<!doctype html>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="${WM_SkinURL}style.css"/>
${php:skin.drawHeadTags()}

<header>
   <div>
      <div id="siteName"><a href="${WM_AdminURL}">${siteName}</a></div>
      <div id="siteSlogan">Panel admina (<a href="${WM_SiteURL}">przejdź do strony →</a>)</div>
   </div>
</header>

<nav>
   <ul id="navLeft">
      ${php:skin.drawLeftNav()}
   </ul>
   <ul id="navRight">
      ${php:skin.drawRightNav()}
   </ul>
</nav>
<div id="container">
   ${php:skin.drawSubNav()}
   <div id="content">
      ${php:skin.drawMessages()}
      <h1 tal:condition="php: !empty(pageTitle) AND !dontShowPageTitle">${pageTitle}</h1>
      ${structure content}
   </div>
</div>
<footer>
   powered by <strong>Watermelon CMS</strong>
   <tal:block tal:condition="php:defined('WM_Debug')">
      <br/><br/>
      Wygenerowano w: <?= round(Benchmark::executionTime(), -2) / 1000 ?> ms<br/>
      Peak memory usage: <?= (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br/>
      Current memory usage: <?= (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br/>
      
      <br/>Zapytania wykonane do bazy danych (<?= count(DB::$queriesArray)?>):<br/>
      
      <ul style="text-align:left; font-size:11px">
         <?
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
   </tal:block>
</footer>

${php:skin.drawTailTags()}
<script src="${WM_SkinURL}ACPSkin.js"/>
<?php die?>
<!doctype html>
<meta charset="UTF-8" />
<link rel="stylesheet" href="${WM_SystemURL}core/Turbine/css.php?files=wcmslay_skin/style.cssp" />
${php:skin.drawHeadTags()}

<header>
   <div>
      <div id="siteName"><a href="${WM_SiteURL}">${siteName}</a></div>
      <div id="siteSlogan" tal:condition="php: !empty(siteSlogan)">${siteSlogan}</div>
   </div>
</header>

<nav>
   <ul id="nav">
      ${php:skin.drawTextMenu(0)}
      
      <tal:block tal:condition="php:Auth::isLogged()">
         <li style="float:right; padding-right:5px"><a href="${php:SiteURL('%/')}">Panel Admina</a></li>
         <li style="float:right"><a href="${php:SiteURL('auth/login')}">Wyloguj</a></li>
      </tal:block>
   </ul>
</nav>

<div id="container">
   <div id="content">
      ${php:skin.drawMessages()}
      <h1 tal:condition="php: !empty(pageTitle) AND !dontShowPageTitle">${pageTitle}</h1>
      ${structure content}
   </div>
</div>

<footer>
   ${structure footer}<br/>
   powered by <strong>Watermelon CMS</strong>
   <tal:block tal:condition="php:defined('WM_Debug')">
      <br/><br/>
      Wygenerowano w: <?= round(Benchmark::executionTime(), -2) / 1000 ?> ms<br/>
      Peak memory usage: <?= (int) ((memory_get_peak_usage() - WM_StartMemory) / 1000) ?> KB<br/>
      Current memory usage: <?= (int) ((memory_get_usage() - WM_StartMemory) / 1000) ?> KB<br/>
      
      <br/>Zapytania wykonane do bazy danych (<?php echo count(DB::$queriesArray)?>):<br/>
      
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
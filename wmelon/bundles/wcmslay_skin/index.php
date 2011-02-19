<?php die?>
<!doctype html>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="${WM_SystemURL}core/Turbine/css.php?files=wcmslay_skin/style.cssp" />
${php:skin.drawHeadTags()}

<header>
   <div>
      <div id="siteName"><a href="${WM_SiteURL}">${siteName}</a></div>
      <div id="siteSlogan" tal:condition="php: !empty(siteSlogan)">${siteSlogan}</div>
   </div>
   
   <nav>
      ${php:skin.drawTextMenu(0)}

      <tal:block tal:condition="isAdmin">
         <a href="${php:SiteURL('%/')}" style="float:right; margin-right:5px">Panel Admina</a>
         <a href="${php:SiteURL('users/logout')}" style="float:right">Wyloguj</a>
      </tal:block>
   </nav>
</header>

<div id="container">
   <section id="content">
      ${php:skin.drawMessages()}
      <h1 tal:condition="php: !empty(pageTitle) AND !noHeader">${pageTitle}</h1>
      ${structure content}
   </section>
</div>

<footer>
   ${structure footer}<br/>
   powered by <strong><a href="https://github.com/radex/Watermelon">Watermelon</a></strong>
   ${structure php: Watermelon_Blockset::debugInfo()}
</footer>
${php:skin.drawTailTags()}
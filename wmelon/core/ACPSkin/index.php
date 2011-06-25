<?php die?>
<!doctype html>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="${SkinURL}style.css?2"/>
${php:skin.drawHeadTags()}

<header>
   <div>
      <div id="siteName"><a href="${AdminURL}">${siteName}</a></div>
      <div id="siteSlogan">Panel admina (<a href="${SiteURL}">przejdź do strony →</a>)</div>
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
      <h1 tal:condition="php: !empty(pageTitle) AND !noHeader">${pageTitle}</h1>
      ${structure content}
   </div>
</div>
<footer>
   powered by <strong>Watermelon</strong>
   ${structure php: Watermelon_Blockset::debugInfo()}
</footer>

${php:skin.drawTailTags()}
<script src="${SkinURL}ACPSkin.js"/>
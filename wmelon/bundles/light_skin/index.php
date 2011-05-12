<?php die?>
<!DOCTYPE html>
<meta charset="UTF-8"/>
${php:skin.drawHeadTags()}
<link rel="stylesheet" href="${BundlesURL}watermelon/public/basic.css"/>
<link rel="stylesheet" href="${SkinURL}public/style.css"/>


<div id="container">
   <header>
      <div>
         <h1>Some stupid name noone cares about</h1>
      </div>
      <nav>
         ${php:skin.drawTextMenu(0)}
         <a href="${AdminURL}" tal:condition="isAdmin" style="float: right">Panel Admina</a>
      </nav>
   </header>
   <section id="content">
      ${php:skin.drawMessages()}
      <h2 tal:condition="php: !empty(pageTitle) AND !noHeader">${pageTitle}</h2>
      ${php:skin.drawContent()}
   </section>
</div>

<footer>
   ${structure footer}
   ${structure php: Watermelon_Blockset::debugInfo()}
</footer>

${php:skin.drawTailTags()}
<?='<!-- powered by Watermelon: <https://github.com/radex/Watermelon> -->'?>
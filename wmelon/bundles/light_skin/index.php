<?php die?>
<!DOCTYPE html>
<meta charset="UTF-8"/>
${php:skin.drawHeadTags()}
<link rel="stylesheet" href="${SkinURL}public/style.css.php?1"/>


<div id="container">
   <header>
      <h1><a href="${SiteURL}">${siteName}</a></h1>
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
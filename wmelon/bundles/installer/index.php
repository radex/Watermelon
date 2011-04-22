<?php die?>
<!DOCTYPE html>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="${SkinURL}public/style.css"/>
${php:skin.drawHeadTags()}

<style>#container, header { display: none }</style>

<!-- header -->

<header><div/></header>

<!-- no js -->

<noscript>
   <p>Instalator Watermelona wymaga do działania Javascriptu.</p>
</noscript>

<!-- container -->

<div id="container">
   <div id="container-inner">
      <div id="content">
         <div id="content-inner">
            ${structure views}
         </div>
      </div>
      <div id="status-bar">
         <input id="next-button" type="button" value="Dalej" />
         <input id="previous-button" type="button" value="Wróć"/>
         <div id="progress-bar-container">
            <div id="progress-bar">
               <div id="progress-bar-progress"></div>
            </div>
            Postęp instalacji
         </div>
      </div>
      
   </div>
   <div class="shadow-top">
      <div class="left"></div>
      <div class="center"></div>
      <div class="right"></div>
   </div>
   <div class="shadow-middle">
      <div class="left"></div>
      <div class="right"></div>
   </div>
   <div class="shadow-bottom">
      <div class="left"></div>
      <div class="center"></div>
      <div class="right"></div>
   </div>
</div>

<!-- *** -->

<script src="${SystemURL}core/FrontendLibraries/jquery.js"></script>
<script src="${SkinURL}public/animations.js"></script>
<script src="${SkinURL}public/installer.js"></script>
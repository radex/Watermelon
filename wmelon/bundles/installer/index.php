<?php die?>
<!doctype html>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="${WM_BundlesURL}watermelon/public/basic.css"/>
<link rel="stylesheet" href="${WM_BundlesURL}watermelon/public/watermelon.css"/>
<link rel="stylesheet" href="${WM_SystemURL}core/Turbine/css.php?files=installer/installer.cssp"/>
${php:skin.drawHeadTags()}

<header></header>

<!-- Container layout -->

<tal:block tal:condition="not: exists: additionalData/noContainer">
   
   ${structure additionalData/formOpen}
   
   <div id="container">
      <div id="content">
         <h1>${pageTitle}</h1>
      
         ${php:skin.drawMessages()}
      
         ${structure content}
      </div>
      <div id="status-bar">
      
         <!-- buttons -->
      
         <input type="submit" value="Dalej" autofocus="true"/>
      
         <input tal:condition="true: additionalData/previous" type="button" onclick="window.location='${WM_SiteURL}${additionalData/previous}'" value="Wróć"/>
      
         <input tal:condition="not: additionalData/previous" type="button" value="Wróć" disabled="true"/>
      
         <!-- progress bar -->
      
         <div id="progress-bar-container">
            <div id="progress-bar">
               <div tal:condition="php: additionalData.progress > 0" id="progress-bar-progress" style="width:${additionalData/progress}%"/>
            </div>
            Postęp instalacji
         </div>
      </div>
   </div>
   
   ${structure additionalData/formClose}
   
</tal:block>

<!-- Container-less layout -->

<tal:block tal:condition="exists: additionalData/noContainer">
   
   ${structure content}

</tal:block>
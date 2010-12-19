<?php die?>

<div id="lang-chooser">
   <a href="$/2/${lang/0}" tal:repeat="lang langs">
      <img tal:attributes="src lang/2" alt="${lang/1}" />
      ${lang/1}
   </a>
</div>
            
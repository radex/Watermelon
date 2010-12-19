<?php die?>
<tal:block>
   <tal:block tal:condition="not: isLogged">
      Użytkownik jest <strong>niezalogowany</strong>
   </tal:block>
   <tal:block tal:condition="isLogged">
      <tal:block tal:repeat="item userData">
         <strong>${repeat/item/key}</strong>: ${item}<br />
      </tal:block>
      <hr />
      <strong>Privileges:</strong>
      <tal:block tal:repeat="item privileges">${item}, </tal:block>
   </tal:block>
</tal:block>
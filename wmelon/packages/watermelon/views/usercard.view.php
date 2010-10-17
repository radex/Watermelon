<?die?>
<tal:block>
   <tal:block tal:condition="not: isLogged">
      Użytkownik jest <strong>niezalogowany</strong>
   </tal:block>
   <tal:block tal:condition="isLogged">
      <?
         
         var_dump($ctx->userData);
         
      ?>
   </tal:block>
</tal:block>
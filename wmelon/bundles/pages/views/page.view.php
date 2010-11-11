<?die?>
<tal:block>
   <article>
      <h1>${page/page_title}<? if(Auth::isLogged()): ?>
         <span class="adminTools"><a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a></span>
      <? endif; ?></h1>
      ${structure page/page_content}
   </article>
   ${structure commentsView}
</tal:block>
<?die?>
<tal:block>
   <article>
      <h1>${page/page_title}
         <span class="adminTools" tal:condition="php: Auth::isLogged()"><a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a></span>
      </h1>
      ${structure page/page_content}
   </article>
   ${structure commentsView}
</tal:block>
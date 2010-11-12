<?die?>
<tal:block>
   <article>
      <h1>${post/title}
         <span class="adminTools" tal:condition="php: Auth::isLogged()"><a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a></span>
      </h1>
      ${structure post/content}
   </article>
   ${structure commentsView}
</tal:block>
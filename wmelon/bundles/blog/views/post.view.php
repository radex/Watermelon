<?die?>
<tal:block>
   <article>
      <h1>${post/blogpost_title}
         <span class="adminTools" tal:condition="php: Auth::isLogged()"><a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a></span>
      </h1>
      ${structure post/blogpost_content}
   </article>
   ${structure commentsView}
</tal:block>
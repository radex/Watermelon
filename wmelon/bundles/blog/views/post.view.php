<?die?>
<tal:block>
   <article>
      <h1>${post/blogpost_title}<? if(Auth::isLogged()): ?>
         <span class="adminTools"><a href="%/blog/edit/${post/blogpost_id}">[Edytuj]</a> | <a href="%/blog/delete/${post/blogpost_id}">[Usuń]</a></span>
      <? endif; ?></h1>
      ${structure post/blogpost_content}
   </article>
   ${structure commentsView}
</tal:block>
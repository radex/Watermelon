<?php die?>
<tal:block>
   <article>
      <header>
         <h1>
            <a href="$/${page/name}">${page/title}</a>
            <span class="adminTools" tal:condition="isAdmin">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </h1>
      </header>
      ${structure page/content}
   </article>
   ${structure commentsView}
</tal:block>
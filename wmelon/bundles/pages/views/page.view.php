<?php die?>
<tal:block>
   <article>
      <header>
         <h1><a href="$/${page/name}">${page/title}</a></h1>
         <span class="postInfo">
            <span class="adminTools" tal:condition="isAdmin">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </span>
      </header>
      ${structure page/content}
   </article>
   ${structure commentsView}
</tal:block>
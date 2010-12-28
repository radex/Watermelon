<?php die?>
<tal:block>
   <article>
      <header>
         <h1>
            ${post/title}
            <span class="h1-comment">${structure php:HumanDate(post.created, true, true)}</span>
            <span class="adminTools" tal:condition="php: Auth::isLogged()">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </h1>
      </header>
      ${structure post/content}
   </article>
   ${structure commentsView}
</tal:block>
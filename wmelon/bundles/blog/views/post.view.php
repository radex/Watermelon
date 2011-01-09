<?php die?>
<tal:block>
   <article>
      <header>
         <h1>
            <a href="${post/url}">${post/title}</a>
            <span class="h1-comment">${structure published_human}</span>
            <span class="adminTools" tal:condition="php: Auth::isLogged()">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </h1>
      </header>
      ${structure post/content}
   </article>
   ${structure commentsView}
</tal:block>
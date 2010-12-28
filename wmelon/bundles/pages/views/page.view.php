<?php die?>
<tal:block>
   <article>
      <header>
         <h1>
            ${page/title}
            <span class="adminTools" tal:condition="php: Auth::isLogged()">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </h1>
      </header>
      ${structure page/content}
   </article>
   ${structure commentsView}
</tal:block>
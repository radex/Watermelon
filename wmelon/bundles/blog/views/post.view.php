<?php die?>
<tal:block>
   <article class="blogpost">
      <header>
         <h1><a href="${post/url}">${post/title}</a></h1>
         <span class="postInfo">
            ${structure published_human}
            
            <span class="adminTools" tal:condition="isAdmin">
               <a href="${editHref}">[Edytuj]</a> | <a href="${deleteHref}">[Usuń]</a>
            </span>
         </span>
      </header>
      ${structure post/content}
   </article>
   ${structure commentsView}
</tal:block>
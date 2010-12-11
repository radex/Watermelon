<?die?>
<tal:block>
   <h1 id="comments-link">
      Komentarze
      <span class="h1-comment">${commentsCount} ${php:pl_inflect(commentsCount, 'komentarzy', 'komentarz', 'komentarze')}</span>
   </h1>
   
   <tal:block tal:condition="areComments" tal:repeat="comment comments">
      <article class="comment" id="comment-${comment/id}" tal:condition="php: Auth::isLogged() OR !comment.awaitingModeration">
         
         <header tal:condition="not: comment/authorID">
            <img src="http://gravatar.com/avatar/${php: md5(comment.authorEmail)}?s=64&d=mm" />
            <tal:block tal:condition="php: false">
               <strong tal:condition="php: comment.authorWebsite"><a href="${comment/authorWebsite}" rel="nofollow">${comment/authorName}</a></strong>
               <strong tal:condition="php: !comment.authorWebsite">${comment/authorName}</strong>
               
               Fix it!
            </tal:block>
            <strong>${comment/authorName}</strong>
         </header>
         
         <header tal:condition="comment/authorID">
            <img src="http://gravatar.com/avatar/<?= md5($ctx->users[$ctx->comment->authorID]->email) ?>?s=64&d=mm" />
            <strong><?= $ctx->users[$ctx->comment->authorID]->nick ?></strong>
         </header>
      
         <section>
            <div class="adminTools" tal:condition="php: Auth::isLogged()">
               <strong tal:condition="comment/awaitingModeration">
                  Niesprawdzony!
               </strong>
               <a href="${comment/editHref}">[Edytuj]</a> |
               <a href="${comment/deleteHref}">[Usuń]</a>
               
               <tal:block tal:condition="not: comment/authorID">
                  |
                  <a href="${comment/approveHref}" tal:condition="comment/awaitingModeration">[Zatwierdź]</a>
                  <a href="${comment/rejectHref}" tal:condition="not: comment/awaitingModeration">[Odrzuć]</a>
               </tal:block>
               
            </div>
            <? echo Textile::textileRestricted($ctx->comment->content) ?>
         </section>
         
      </article>
   </tal:block>
   
   <p tal:condition="not: areComments">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </p>
   
   <h1 id="commentForm-link">Napisz komentarz</h1>
   ${structure form}${structure php: Sblam::JS()}
</tal:block>
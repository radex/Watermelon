<?php die?>
<section class="comments">
   <h1 id="comments-link">
      Komentarze
      <span class="h1-comment">${structure commentsCount}</span>
   </h1>
   
   <!-- Comment -->
   
   <tal:block tal:condition="areComments" tal:repeat="comment comments">
      <article tal:attributes="class comment/cssClass | nothing" id="comment-${comment/id}" tal:condition="php: Auth::isLogged() OR !comment.awaitingModeration">
         
         <!-- header (for anonymous user) -->
         
         <header tal:condition="not: comment/authorID">
            <img src="${comment/gravatarURL}" alt="" />
            <tal:block tal:condition="php: false">
               <strong tal:condition="php: comment.authorWebsite"><a href="${comment/authorWebsite}" rel="nofollow">${comment/authorName}</a></strong>
               <strong tal:condition="php: !comment.authorWebsite">${comment/authorName}</strong>
               
               Fix it!
            </tal:block>
            <strong>${comment/authorName}</strong>
         </header>
         
         <!-- header (for logged user) -->
         
         <header tal:condition="comment/authorID">
            <img src="${comment/gravatarURL}" alt="" />
            <strong><?= $ctx->users[$ctx->comment->authorID]->nick ?></strong>
         </header>
         
         <!-- content -->
         
         <section>
            
            <!-- admin tools -->
            
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
            
            <!-- content itself -->
            
            <?= Textile::textileRestricted($ctx->comment->content) ?>
         </section>
         
      </article>
   </tal:block>
   
   <!-- no comments -->
   
   <p tal:condition="not: areComments">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </p>
   
   <!-- comment form -->
   
   <h1 id="commentForm-link">Napisz komentarz</h1>
   ${structure form}${structure php: Sblam::JS()}
</section>
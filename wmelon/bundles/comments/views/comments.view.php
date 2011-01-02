<?php die?>
<section class="comments">
   <h1 id="comments-link">
      Komentarze
      <span class="h1-comment">${structure commentsCount}</span>
   </h1>
   
   <!-- Comment -->
   
   <tal:block tal:condition="areComments" tal:repeat="comment comments">
      <article tal:attributes="class comment/cssClass | nothing" id="comment-${comment/id}" tal:condition="comment/visible">
         
         <!-- header (for anonymous user's comment) -->
         
         <header tal:condition="not: comment/authorID">
            <img src="${comment/gravatarURL}" alt="" />
            <strong tal:attributes="title comment/additionalInfo | nothing">
               <a href="${comment/authorWebsite}" rel="nofollow" tal:omit-tag="not: comment/authorWebsite">${comment/authorName}</a>
            </strong>
         </header>
         
         <!-- header (for logged user's post) -->
         
         <header tal:condition="comment/authorID">
            <img src="${comment/gravatarURL}" alt="" />
            <strong><a href="${WM_SiteURL}"><?= $ctx->users[$ctx->comment->authorID]->nick ?></a></strong>
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
            
            <!-- "not approved" - for not logged users -->
            
            <div class="adminTools" tal:condition="php: !Auth::isLogged() AND comment.awaitingModeration">
               Komentarz oczekuje na moderację
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
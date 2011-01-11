<?php die?>
<section class="comments">
   <h1 id="comments-link" tal:condition="php: areComments">
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
         
         <header tal:condition="exists: comment/author">
            <img src="${comment/gravatarURL}" alt="" />
            <strong><a href="${WM_SiteURL}">${comment/author/nick}</a></strong>
         </header>
         
         <!-- content -->
         
         <section>
            
            <!-- admin tools -->
            
            <div class="adminTools" tal:condition="isAdmin">
               <strong tal:condition="not: comment/approved">
                  Niesprawdzony!
               </strong>
               <a href="${comment/editHref}">[Edytuj]</a> |
               <a href="${comment/deleteHref}">[Usuń]</a>
               
               <tal:block tal:condition="not: comment/authorID">
                  |
                  <a href="${comment/approveHref}" tal:condition="not: comment/approved">[Zatwierdź]</a>
                  <a href="${comment/rejectHref}" tal:condition="comment/approved">[Odrzuć]</a>
               </tal:block>
            </div>
            
            <!-- "not approved" - for not logged users -->
            
            <div class="adminTools" tal:condition="php: !Auth::isLogged() && !comment.approved">
               Komentarz oczekuje na moderację
            </div>
            
            <!-- content itself -->
            
            <?= Textile::textileRestricted($ctx->comment->content) ?>
         </section>
         
      </article>
   </tal:block>
   
   <!-- comment form -->
   
   <tal:block tal:condition="open">
      <h1 id="commentForm-link">Napisz komentarz</h1>
      ${structure form}${structure php: Sblam::JS()}
   </tal:block>
   
   <!-- "commenting has been disabled" message -->
   
   <div tal:condition="not:open" class="comments-closed">Komentowanie zostało wyłączone</div>
</section>
<?php die?>
<section id="comments">
   <h1 tal:condition="php: areComments">
      Komentarze <span class="headerComment">${structure commentsCount}</span>
   </h1>
   
   <!-- Comment -->
   
   <tal:block tal:condition="areComments" tal:repeat="comment comments">
      <article tal:attributes="class comment/cssClass | nothing" id="comment-${comment/id}" tal:condition="comment/visible">
         
         <!-- header (for anonymous user's comment) -->
         
         <header tal:condition="not: comment/authorID">
            <span tal:attributes="title comment/additionalInfo | nothing">
               <a href="${comment/authorWebsite}" rel="nofollow" tal:omit-tag="not: comment/authorWebsite">${comment/authorName}</a>
            </span>
         </header>
         
         <!-- header (for logged user's post) -->
         
         <header tal:condition="exists: comment/author">
            <strong><a href="${SiteURL}">${comment/author/nick}</a></strong>
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
            
            <div class="adminTools" tal:condition="php: !isAdmin && !comment.approved">
               Komentarz oczekuje na moderację
            </div>
            
            <!-- content itself -->
            
            ${structure php:Textile::textileRestricted(comment.content)}
         </section>
         
      </article>
   </tal:block>
   
   <!-- comment form -->
   
   <tal:block tal:condition="open">
      <h1 id="commentForm-link">Napisz komentarz</h1>
      ${structure form}${structure php: Sblam::JS()}
   </tal:block>
   
   <!-- "commenting has been disabled" message -->
   
   <div tal:condition="php: !open && visibleCount > 0" class="comments-closed">Komentowanie zostało wyłączone</div>
</section>
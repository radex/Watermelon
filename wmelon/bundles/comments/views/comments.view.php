<?die?>
<tal:block>
   <h1>Komentarze</h1>
   <tal:block tal:condition="php: comments.exists" tal:repeat="comment comments">
      
      <article class="comment" tal:condition="php: !comment.awaitingModeration">
         
         <header>
            <img src="http://gravatar.com/avatar/${php: md5(comment.authorEmail)}?s=64&d=mm" /><br />
            <strong tal:condition="php: comment.authorWebsite"><a href="${comment/authorWebsite}" rel="nofollow">${comment/authorName}</a></strong>
            <strong tal:condition="php: !comment.authorWebsite">${comment/authorName}</strong>
         </header>
         
         <section>
            <div class="comment-tools" tal:condition="php: Auth::isLogged()">
               (linki)
            </div>
            <? echo Textile::textile($ctx->comment->text) ?>
         </section>
         
      </article>
      
   </tal:block>
   
   <tal:block tal:condition="php: !comments.exists">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </tal:block>
   
   <h1 id="commentForm-link">Napisz komentarz</h1>
   ${structure form}${structure php: Sblam::JS()}
</tal:block>
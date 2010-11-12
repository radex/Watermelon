<?die?>
<tal:block>
   <h1>Komentarze</h1>
   <article class="comment" tal:repeat="comment comments" tal:condition="php: comments.exists">
      <header>
         <img src="http://gravatar.com/avatar/<? echo md5($ctx->comment->authorEmail) ?>?s=64&d=mm" /><br />
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
   
   <tal:block tal:condition="php: !comments.exists">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </tal:block>
   
   <h1 id="commentForm-link">Napisz komentarz</h1>
   ${structure form}
</tal:block>
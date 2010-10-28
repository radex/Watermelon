<?die?>
<tal:block>
   <h1>Komentarze</h1>
   <article class="comment" tal:repeat="comment comments" tal:condition="php: comments.exists">
      <header>
         <img src="http://gravatar.com/avatar/<? echo md5($ctx->comment->comment_authorEmail) ?>?s=64&d=mm" /><br />
         <strong tal:condition="php: comment.comment_authorWebsite"><a href="${comment/comment_authorWebsite}" rel="nofollow">${comment/comment_authorName}</a></strong>
         <strong tal:condition="php: !comment.comment_authorWebsite">${comment/comment_authorName}</strong>
      </header>
      <section>
         <? echo Textile::textile($ctx->comment->comment_text) ?>
      </section>
   </article>
   
   <tal:block tal:condition="php: !comments.exists">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </tal:block>
   
   <h1 id="commentForm-link">Napisz komentarz</h1>
   ${structure form}
</tal:block>
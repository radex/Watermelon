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
   
   <h1>Napisz komentarz</h1>
   <!-- make *better* form -->
   
   <form action="$/comments/post/${id}/${type}/${php: base64_encode(backPage)}" method="post">
      <label>
         Imię:
         <input name="name" required="required" />
      </label>
      <br />
      <label>
         Email:
         <input type="email" name="email" required="required" />
      </label>
      <br />
      <label>
         Strona (opcjonalnie):
         <input type="url" name="website" />
      </label>
      <br />
      <label>
         Treść komentarza:<br />
         <textarea name="text" required="required" style="width: 350px; height: 150px" />
      </label>
      <br />
      <input type="submit" value="Zapisz" />
   </form>
   
   <hr id="_commentForm" />
   ${structure form}
</tal:block>
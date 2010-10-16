<?defined('WM') or die?>
<tal:block>
   <h1>Komentarze</h1>
   <article class="comment" tal:repeat="comment comments" tal:condition="php: comments.exists">
      <header>
         Posted by (...)
      </header>
      <section>
      ${structure comment/comment_text}
      </section>
   </article>
   
   <tal:block tal:condition="php: !comments.exists">
      Nie ma tutaj komentarzy. Napisz pierwszego!
   </tal:block>
   
   <h1>Napisz komentarz</h1>
   (soon)
</tal:block>
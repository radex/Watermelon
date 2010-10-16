<?defined('WM') or die?>
<tal:block>
   <tal:block tal:content="structure post/blogpost_content" />
   <h1>Komentarze</h1>
   <!-- TODO: show comments using loaded view from comments module -->
   <article class="comment" tal:repeat="comment comments">
      <header>
         Posted by (...)
      </header>
      ${structure comment/comment_text}
   </article>
</tal:block>
<?die?>
<tal:block>
   <article>
      <h1>${post/blogpost_title}</h1>
      ${structure post/blogpost_content}
   </article>
   ${structure commentsView}
</tal:block>
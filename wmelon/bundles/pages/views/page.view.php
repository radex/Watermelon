<?die?>
<tal:block>
   <article>
      <h1>${page/page_title}</h1>
      ${structure page/page_content}
   </article>
   ${structure commentsView}
</tal:block>
<?die?>
<section tal:repeat="post posts">
   <article>
      <header>
         <a href="$/blog/post/${post/id}">
            <h1>${post/title}</h1>
         </a>
      </header>
      <tal:block tal:condition="not:post/beginning">
         <? echo Textile::textile($ctx->post->content) ?>
      </tal:block>
      <tal:block tal:condition="post/beginning">
         <? echo Textile::textile($ctx->post->beginning) ?> (...)
      </tal:block>
   </article>
   <div class="blog-post-databox">
      <? echo Model('comments')->countCommentsFor($ctx->post->id, 'blogpost', false) ?> komentarzy | <a href="$/blog/post/${post/id}">Dalej »</a>
   </div>
</section>

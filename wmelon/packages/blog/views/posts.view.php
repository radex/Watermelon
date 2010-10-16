<?die?>
<section tal:repeat="post posts">
   <article>
      <header>
         <a href="blog/post/${post/blogpost_id}">
            <h1>${post/blogpost_title}</h1>
         </a>
      </header>
      <tal:block tal:condition="not:post/blogpost_beginning">
         <? echo Textile_Extension::textile($ctx->post->blogpost_content) ?>
      </tal:block>
      <tal:block tal:condition="post/blogpost_beginning">
         <? echo Textile_Extension::textile($ctx->post->blogpost_beginning) ?> (...)
      </tal:block>
   </article>
   <div class="blog-post-databox">
      <? echo Model('comments')->countCommentsFor($ctx->post->blogpost_id, 'blogposts') ?> komentarzy | <a href="blog/post/${post/blogpost_id}">Dalej »</a>
   </div>
</section>

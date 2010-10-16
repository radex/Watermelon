<?die?>
<div tal:repeat="post posts">
   <a href="blog/post/${post/blogpost_id}"><h1>${post/blogpost_title}</h1></a>
   <p tal:condition="not:post/blogpost_beginning">${post/blogpost_content}</p>
   <p tal:condition="post/blogpost_beginning">${post/blogpost_beginning} (...)</p>
   <div class="blog-post-databox">
      x komentarzy | <a href="blog/post/${post/blogpost_id}">Dalej »</a>
   </div>
</div>

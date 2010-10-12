<?defined('WM') or die?>
<div tal:repeat="post posts">
   <a href="blog/post/${post/blogpost_id}"><h1>${post/blogpost_title}</h1></a>
   <p tal:condition="not:post/blogpost_beginning">${post/blogpost_content}</p>
   <p tal:condition="post/blogpost_beginning">${post/blogpost_beginning}</p>
   <a tal:condition="post/blogpost_beginning" href="blog/post/${post/blogpost_id}" class="dr">Czytaj dalej...</a>
</div>

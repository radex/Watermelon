<?php die?>

<!-- Pages -->

<div class="blog-pages" tal:condition="php: page > 1">
   <a href="${previousPage}" class="blog-previousPage" tal:condition="php: page > 1">« Nowsze wpisy</a>
   <div class="blog-previousPage" tal:condition="php: page == 1" />
   
   <span class="blog-page">${page}</span>
   
   <a href="${nextPage}" class="blog-nextPage" tal:condition="anotherPage">Starsze wpisy »</a>
   <div class="blog-nextPage" tal:condition="not: anotherPage" />
</div>

<!-- Posts -->

<article tal:repeat="post posts" id="blogpost-${post/id}" class="blogpost">
   
   <!-- header -->
   
   <header>
      <h1><a href="${post/url}">${post/title}</a></h1>
      
      <span class="postInfo">
         ${structure post/published_human}${structure post/comments | nothing}
         <span class="adminTools" tal:condition="isAdmin">
            <a href="${post/editHref}">[Edytuj]</a> | <a href="${post/deleteHref}">[Usuń]</a>
         </span>
      </span>
   </header>
   
   <!-- post content -->
   
   <tal:block tal:condition="not: post/summary">
      ${structure post/content}
   </tal:block>
   <tal:block tal:condition="post/summary">
      ${structure post/summary}
   </tal:block>
   
   <!-- read more -->
   
   <p><a class="blog-readMore" href="${post/url}">Czytaj dalej »</a></p>
</article>

<!-- Pages -->

<div class="blog-pages" tal:condition="php: page > 1 || anotherPage">
   <a href="${previousPage}" class="blog-previousPage" tal:condition="php: page > 1">« Nowsze wpisy</a>
   <div class="blog-previousPage" tal:condition="php: page == 1" />
   
   <span class="blog-page">${page}</span>
   
   <a href="${nextPage}" class="blog-nextPage" tal:condition="anotherPage">Starsze wpisy »</a>
   <div class="blog-nextPage" tal:condition="not: anotherPage" />
</div>

<!-- if no posts -->

<p tal:condition="php: count(posts) == 0">
   Brak wpisów. <a tal:attributes="href string:%/blog/new" tal:condition="isAdmin">Napisz pierwszego!</a>
</p>
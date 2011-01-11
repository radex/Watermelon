<?php die?>

<!-- Pages -->

<div class="blog-pages-top" tal:condition="php: page > 1">
   <a href="${previousPage}" class="blog-previousPage" tal:condition="php: page > 1">« Nowsze wpisy</a>
   <div class="blog-previousPage" tal:condition="php: page == 1" />
   
   <span class="blog-page">${page}</span>
   
   <a href="${nextPage}" class="blog-nextPage" tal:condition="anotherPage">Starsze wpisy »</a>
   <div class="blog-nextPage" tal:condition="not: anotherPage" />
</div>

<!-- Posts -->

<article tal:repeat="post posts" id="blogpost-${post/id}">
   
   <!-- header -->
   
   <header>
      <h1>
         <a href="${post/url}">${post/title}</a>
         <span class="h1-comment">
            ${structure post/published_human}${structure post/comments | nothing}
         </span>
         <span class="adminTools" tal:condition="isAdmin">
            <a href="${post/editHref}">[Edytuj]</a> | <a href="${post/deleteHref}">[Usuń]</a>
         </span>
      </h1>
   </header>
   
   <!-- post content -->
   
   <article tal:condition="not: post/summary">
      ${structure post/content}
   </article>
   <article tal:condition="post/summary">
      ${structure post/summary}
   </article>
   
   <!-- read more -->
   
   <a class="blog-readMore" href="${post/url}">Czytaj dalej »</a>
</article>

<!-- Pages -->

<div class="blog-pages-bottom" tal:condition="php: page > 1 || anotherPage">
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
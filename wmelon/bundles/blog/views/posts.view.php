<?die?>

<div class="blog-pages-top" tal:condition="php: page > 1">
   <a href="${previousPage}" class="blog-previousPage" tal:condition="php: page > 1">« Nowsze wpisy</a>
   <div class="blog-previousPage" tal:condition="php: page == 1" />
   
   <span class="blog-page">${page}</span>
   
   <a href="${nextPage}" class="blog-nextPage" tal:condition="anotherPage">Starsze wpisy »</a>
   <div class="blog-nextPage" tal:condition="not: anotherPage" />
</div>

<article tal:repeat="post posts" id="blogpost-${post/id}">
   <header>
      <h1>
         <a href="$/blog/${post/name}">${post/title}</a>
         <span class="h1-comment">
            ${structure post/created_human}, ${post/comments} ${php:pl_inflect(post.comments, 'komentarzy', 'komentarz', 'komentarze')}
         </span>
         <span class="adminTools" tal:condition="php: Auth::isLogged()">
            <a href="${post/editHref}">[Edytuj]</a> | <a href="${post/deleteHref}">[Usuń]</a>
         </span>
      </h1>
   </header>
   <article tal:condition="not: post/summary">
      <?= Textile::textile($ctx->post->content) ?>
   </article>
   <article tal:condition="post/summary">
      <?= Textile::textile($ctx->post->summary . ' <em>[...]</em>') ?>
   </article>
   <a class="blog-readMore" href="$/blog/${post/name}">Czytaj dalej »</a>
</article>

<div class="blog-pages-bottom" tal:condition="php: page > 1 || anotherPage">
   <a href="${previousPage}" class="blog-previousPage" tal:condition="php: page > 1">« Nowsze wpisy</a>
   <div class="blog-previousPage" tal:condition="php: page == 1" />
   
   <span class="blog-page">${page}</span>
   
   <a href="${nextPage}" class="blog-nextPage" tal:condition="anotherPage">Starsze wpisy »</a>
   <div class="blog-nextPage" tal:condition="not: anotherPage" />
</div>

<p tal:condition="php: count(posts) == 0">
   Brak wpisów. <a tal:attributes="href string:%/blog/new" tal:condition="php: Auth::isLogged()">Napisz pierwszego!</a>
</p>
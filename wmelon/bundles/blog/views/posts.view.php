<?die?>
<section tal:repeat="post posts">
   <article>
      <header>
         <h1>
            <a href="$/blog/${post/name}">${post/title}</a>
            <span class="h1-comment"><?= date('d.m.Y', $ctx->post->created)?>, <?= Model('comments')->countCommentsFor($ctx->post->id, 'blogpost', false) ?> komentarzy</span>
            <span class="adminTools" tal:condition="php: Auth::isLogged()"><a href="${post/editHref}">[Edytuj]</a> | <a href="${post/deleteHref}">[Usuń]</a></span>
         </h1>
      </header>
      <? echo Textile::textile($ctx->post->content) ?>
   </article>
   <a class="blog-readMore" href="$/blog/${post/name}">Czytaj dalej »</a>
</section>

<p tal:condition="php: count(posts) == 0">
   Brak wpisów. <a tal:attributes="href string:%/blog/new" tal:condition="php: Auth::isLogged()">Napisz pierwszy.</a>
</p>
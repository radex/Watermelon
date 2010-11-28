<?die?>
<section tal:repeat="post posts">
   <article>
      <header>
         <a href="$/blog/${post/name}">
            <h1>
               ${post/title}
               <span class="adminTools" tal:condition="php: Auth::isLogged()"><a href="${post/editHref}">[Edytuj]</a> | <a href="${post/deleteHref}">[Usuń]</a></span>
            </h1>
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
      <? echo Model('comments')->countCommentsFor($ctx->post->id, 'blogpost', false) ?> komentarzy | <a href="$/blog/${post/name}">Dalej »</a>
   </div>
</section>

<p tal:condition="php: count(posts) == 0">
   Brak wpisów. <a tal:attributes="href string:%/blog/new" tal:condition="php: Auth::isLogged()">Napisz pierwszy.</a>
</p>
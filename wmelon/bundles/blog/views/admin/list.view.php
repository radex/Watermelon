<?php die?>

<!-- scopes (all, drafts, published, trash) -->

<tal:block tal:condition="php: counts.all + counts.trash > 0">
   <a href="$/blog/" title="Pokaż opublikowane wpisy oraz szkice">Wszystkie (${counts/all})</a>
   
   <tal:block tal:condition="php: counts.drafts > 0">
   | <a href="$/blog/scope:drafts" title="Pokaż szkice">Szkice (${counts/drafts})</a>
   </tal:block>
   
   <tal:block tal:condition="php: counts.published > 0">
   | <a href="$/blog/scope:published" title="Pokaż opublikowane wpisy">Opublikowane (${counts/published})</a>
   </tal:block>
   
   <tal:block tal:condition="php: counts.trash > 0">
   | <a href="$/blog/scope:trash" title="Pokaż wpisy przeniesione do kosza">Kosz (${counts/trash})</a>
   </tal:block>
   
   <br/><br/>
</tal:block>

<!-- if no posts in this scope -->

<p tal:condition="not:exists:posts">
   Brak wpisów. <a href="$/blog/new">Napisz pierwszy.</a>
</p>

<!-- posts table -->

${structure table}
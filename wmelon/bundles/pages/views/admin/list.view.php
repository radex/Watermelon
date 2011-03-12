<?php die?>

<!-- scopes (published, trash) -->

<tal:block tal:condition="php: counts.published + counts.trash > 0">
   <a href="$/pages/" title="Pokaż opublikowane strony">Opublikowane (${counts/published})</a>
   
   <tal:block tal:condition="php: counts.trash > 0">
   | <a href="$/pages/?scope=trash" title="Pokaż strony przeniesione do kosza">Kosz (${counts/trash})</a>
   </tal:block>
   
   <br/><br/>
</tal:block>

<!-- if no posts in this scope -->

<p tal:condition="not:exists:pages">
   Brak stron. <a href="$/pages/new">Utwórz pierwszą.</a>
</p>

<!-- posts table -->

${structure table}
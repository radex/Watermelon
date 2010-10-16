<?defined('WM') or die?>
<tal:block>
   <article tal:content="structure page/page_content" />
   <? Comments_Extension::displayComments($ctx->comments) ?>
</tal:block>
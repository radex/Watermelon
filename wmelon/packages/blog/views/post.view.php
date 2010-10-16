<?defined('WM') or die?>
<tal:block>
   <article tal:content="structure post/blogpost_content" />
   <? Comments_Extension::displayComments($ctx->comments) ?>
</tal:block>
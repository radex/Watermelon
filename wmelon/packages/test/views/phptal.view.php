<?die?>
<tal:block>
   <div tal:content="foo">
      Contents of foo
   </div>
   <div tal:repeat="barItem bar">
      <strong tal:content="barItem" />
   </div>
   
   foo
</tal:block>
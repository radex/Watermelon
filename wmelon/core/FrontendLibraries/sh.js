
 //  
 //  Watermelon
 //  


function sh_path()
{
   var args = arguments, result = [];

   for(var i = 0; i < args.length; i++)
   {
      result.push(args[i].replace('@', SystemURL + '/core/FrontendLibraries/SyntaxHighlighter/brushes/'));
   }

   return result;
}

SyntaxHighlighter.autoloader.apply(null, sh_path(
   'cpp c                  @shBrushCpp.js',
   'css                    @shBrushCss.js',
   'js jscript javascript  @shBrushJScript.js',
   'php                    @shBrushPhp.js',
   'sql                    @shBrushSql.js',
   'xml xhtml xslt html    @shBrushXml.js'
));

SyntaxHighlighter.defaults['toolbar'] = false;

SyntaxHighlighter.all();
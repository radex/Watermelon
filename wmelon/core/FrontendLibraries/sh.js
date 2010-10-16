
 //  
 //  Watermelon CMS
 //  


function path()
{
   var args = arguments, result = [];

   for(var i = 0; i < args.length; i++)
   {
      result.push(args[i].replace('@', 'http://localhost/w/wmelon/core/FrontendLibraries/SyntaxHighlighter/brushes/')); //TODO: fix it
   }

   return result
};

SyntaxHighlighter.autoloader.apply(null, path(
   'cpp c                  @shBrushCpp.js',
   'css                    @shBrushCss.js',
   'js jscript javascript  @shBrushJScript.js',
   'php                    @shBrushPhp.js',
   'sql                    @shBrushSql.js',
   'xml xhtml xslt html    @shBrushXml.js'
));

SyntaxHighlighter.defaults['toolbar'] = false;

SyntaxHighlighter.all();
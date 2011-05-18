<?php die?>

<br/><br/>

<p>
   Generated in: ${generationTime} ms<br />
   Peak memory usage: ${peakMemory} KB<br />
   Current memory usage: ${currentMemory} KB
</p>

<br />

<p>Queries to database (${queriesCount}):</p>

<ul style="text-align:left; font-size:11px">
   <li tal:repeat="query queries">
      <pre style="padding:5px">${query}</pre>
   </li>
</ul>
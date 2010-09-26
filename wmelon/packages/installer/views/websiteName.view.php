<?defined('WM') or die?>

<tal:block>
   <tal:block tal:content="structure errors" />

   Już blisko! Podaj jeszcze tylko nazwę dla Twojej nowej strony.

   <form action="$/7" method="post" name="form">
   
      <div>
         <label>Nazwa strony</label>
         <input type="text" name="siteName" value="${form/siteName}" />
      </div>
   
   </form>
</tal:block>
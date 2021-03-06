<?php die?>

<p>Ustawienia głównego menu strony</p>

<form action="$/options/nav_save" method="post" id="navOptionsForm" data-items="${php:count(menu)}">
   <table>
      <colgroup>
         <col />
         <col />
         <col style="width:50px"/>
         <col />
         <col style="width:120px" />
      </colgroup>
      <thead>
         <tr>
            <th>Tytuł</th>
            <th>URL</th>
            <th><small>Relatywny</small></th>
            <th>Opis</th>
            <th>Opcje</th>
         </tr>
      </thead>
      <tfoot>
         <tr>
            <th>Tytuł</th>
            <th>URL</th>
            <th><small>Relatywny</small></th>
            <th>Opis</th>
            <th>Opcje</th>
         </tr>
      </tfoot>
      <tbody>
         <tr tal:repeat="menuItem menu">
            <td><input name="name_${repeat/menuItem/index}"     type="text"     value="${menuItem/name}" /></td>
            <td><input name="url_${repeat/menuItem/index}"      type="text"     value="${menuItem/url}" /></td>
            <td><input name="relative_${repeat/menuItem/index}" type="checkbox" tal:attributes="checked menuItem/relative" /></td>
            <td><input name="title_${repeat/menuItem/index}"    type="text"     value="${menuItem/title}" /></td>
            <td>
               <tal:block tal:condition="not: repeat/menuItem/start">
                  <a href="#" onclick="navOptions_redirect(${repeat/menuItem/index}, 'top')" title="Na początek">
                     <img alt="Na początek" src="${topIcon}" />
                  </a>
                  <a href="#" onclick="navOptions_redirect(${repeat/menuItem/index}, 'up')" title="Do góry">
                     <img alt="Do góry" src="${upIcon}" />
                  </a>
               </tal:block>
               
               <div tal:condition="repeat/menuItem/start" style="display:inline-block;width:36px;height:16px" />
               
               
               <tal:block tal:condition="not: repeat/menuItem/end">
                  <a href="#" onclick="navOptions_redirect(${repeat/menuItem/index}, 'down')" title="Na dół">
                     <img alt="Na dół" src="${downIcon}" />
                  </a>
                  <a href="#" onclick="navOptions_redirect(${repeat/menuItem/index}, 'bottom')" title="Na koniec">
                     <img alt="Na koniec" src="${bottomIcon}" />
                  </a>
               </tal:block>
               
               <div tal:condition="repeat/menuItem/end" style="display:inline-block;width:36px;height:16px" id="navOptions_bottomPlaceholder" />
               
               &nbsp;&nbsp;
               <a href="#" onclick="navOptions_redirect(${repeat/menuItem/index}, 'delete')" title="Usuń"><img src="${deleteIcon}" /></a>
            </td>
         </tr>
      </tbody>
   </table>
   
   <input type="submit" value="Zapisz zmiany" /> &larr; nie zapomnij zapisać zmian<br/>
   <input type="button" value="Dodaj następny link" onclick="navOptions_redirect(0, 'add')" />
</form>

<br/><br/>

<h2>Objaśnienia do tabeli</h2>
<strong>Tytuł</strong> - nazwa widoczna<br />
<strong>URL</strong> - nazwa podstrony (np. <em>pages/foo</em>), lub pełny adres strony (np. <em>http://example.com/</em>) gdy pole <em>Relatywny</em> jest odznaczone<br />
<strong>Opis</strong> (opcjonalnie) - tekst widoczny po najechaniu myszą<br />
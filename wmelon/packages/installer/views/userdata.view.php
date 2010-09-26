<?defined('WM') or die?>

<tal:block>
   <tal:block tal:content="structure errors" />

   Podaj nick i hasło, które chcesz mieć dla siebie na swojej stronie.

   <form action="$/6" method="post" name="form">
   
      <div>
         <label>Nazwa użytkownika:</label>
         <input type="text" name="user" value="${form/user}" />
      </div>
   
      <div>
         <label>Hasło</label>
         <input type="password" name="pass" value="${form/pass}" />
      </div>
   
      <div>
         <label>
            Hasło (powtórz)
            <span>Aby upewnić się, że nie popełnisz błędu podczas wpisywania</span>
         </label>
         <input type="password" name="pass2" value="${form/pass2}" />
      </div>
   </form>
</tal:block>
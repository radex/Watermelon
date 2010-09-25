<?defined('WM') or die?>
<form action="$/5">
   
   <div>
      <label>
         Nazwa bazy danych:
         <span>Baza o podanej nazwie musi ustnieć, chyba że podany użytkownik ma uprawnienia do tworzenia baz danych</span>
      </label>
      <input type="text" name="name" value="wmelon">
   </div>
   
   <div>
      <label>Nazwa użytkownika:</label>
      <input type="text" name="user">
   </div>
   
   <div>
      <label>Hasło:</label>
      <input type="password" name="pass">
   </div>
   
   <div class="advanced-hr">
      Zaawansowane
      <hr>
   </div>
   
   
   <div>
      <label>
         Serwer:
         <span>Prawie zawsze jest to <em>localhost</em></span>
      </label>
      <input type="text" name="host" value="localhost">
   </div>
   
   <div>
      <label>
         Prefiks nazw tabel:
         <span>Zostaw taki jaki jest, chyba że chcesz mieć kilka kopii Watermelona na jednej bazie danych - wtedy obie muszą mieć ustalony inny prefiks</span>
      </label>
      <input type="text" name="prefix" value="wm_">
   </div>
   
</form>
<?php die?>

<div class="content-box" id="dbinfo">
   <h1>Baza danych</h1>
   
   <div class="messages" />

   <p>Podaj dane dostępu do bazy danych. Na ogół można je znaleźć w panelu administracyjnym serwera. W razie wątpliwości, zapytaj swojego administratora o pomoc.</p>
   
   <form action="#" method="post">
      <label>
         <span>
            Nazwa bazy danych:
            <small>Jeśli nie istnieje, instalator spróbuje ją utworzyć</small>
         </span>
         <input type="text" id="db-name" value="watermelon" />
      </label>

      <label>
         <span>
            Użytkownik:
            <small>Użytkownik z dostępem do podanej bazy danych</small>
         </span>
         <input type="text" id="db-user" />
      </label>

      <label>
         <span>Hasło:</span>
         <input type="password" id="db-pass" />
      </label>

      <div class="advanced-hr">Zaawansowane<hr /></div>
      
      <label>
         <span>
            Prefiks tabel:
            <small>Niezbędny jeśli chcesz mieć dwie kopie Watermelona na jednej bazie danych</small>
         </span>
         <input type="text" id="db-prefix" />
      </label>

      <label> 
         <span>
            Serwer:
            <small>Prawie zawsze jest to <em>localhost</em></small>
         </span>
         <input type="text" id="db-host" value="localhost" />
      </label>
   </form>
</div>
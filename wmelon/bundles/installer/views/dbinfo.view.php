<?php die?>

<div class="content-box" id="dbinfo">
   <h1>Baza danych</h1>
   
   <div class="messages" />

   <p>Podaj dane dostępu do bazy danych. Dane te powinny znajdować się w panelu administracyjnym Twojego serwera. Jeśli nie możesz ich znaleźć, sprawdź dział pomocy na stronie dostawcy Twojego serwera lub skontaktuj się ze swoim administratorem.</p>
   
   <form action="#" method="post">
      <label>
         <span>
            Nazwa bazy danych:
            <small>Jeśli nie istnieje, Watermelon spróbuje utworzyć ją za Ciebie</small>
         </span>
         <input type="text" id="db-name" value="watermelon" />
      </label>

      <label>
         <span>Użytkownik:</span>
         <input type="text" id="db-user" />
      </label>

      <label>
         <span>Hasło:</span>
         <input type="password" id="db-pass" />
      </label>

      <div id="dbinfo-advanced-hr">
         <a href="#">Pokaż zaawansowane <span>&#x25BC;</span></a>
      </div>
      
      <div id="dbinfo-advanced">
         <label>
            <span>
               Prefiks nazw tabel:
               <small>W razie potrzeby, Watermelon wybierze prefiks, który nie powoduje konfliktów</small>
            </span>
            <input type="text" id="db-prefix" />
         </label>
         
         <label> 
            <span>
               Serwer:
               <small>Adres serwera bazy danych</small>
            </span>
            <input type="text" id="db-host" value="localhost" />
         </label>
      </div>
   </form>
</div>
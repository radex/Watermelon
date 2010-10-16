<?die?>

<tal:block>
   <p>To już wszystko! Jeśli popełniłeś gdzieś błąd, cały czas możesz się cofnąć. W przeciwnym wypadku, naciśnij "Dalej", a zostaniesz przeniesiony na swoją nową stronę.</p>

   <p>Oto dane, które podałeś:</p>

   <table>
      <tr>
         <th>Nazwa bazy danych:</th>
         <td>${db/name}</td>
      </tr>
      <tr>
         <th>Użytkownika bazy danych:</th>
         <td>${db/user}</td>
      </tr>
      <tr>
         <th>Hasło:</th>
         <td>${db/pass}</td>
      </tr>
      <tr>
         <th>Serwer:</th>
         <td>${db/host}</td>
      </tr>
      <tr>
         <th>Prefiks nazw tabel:</th>
         <td>${db/prefix}</td>
      </tr>


      <tr>
         <th>Twoja nazwa użytkownika:</th>
         <td>${user/user}</td>
      </tr>
      <tr>
         <th>Twoje hasło:</th>
         <td>${user/pass}</td>
      </tr>


      <tr>
         <th>Nazwa strony:</th>
         <td>${site/siteName}</td>
      </tr>
   </table>
</tal:block>
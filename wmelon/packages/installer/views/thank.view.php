<?defined('WM') or die?>

<p>To już wszystko! Jeśli popełniłeś gdzieś błąd, cały czas możesz się cofnąć. W przeciwnym wypadku, naciśnij "Dalej", a zostaniesz przeniesiony na swoją nową stronę.</p>

<p>Oto dane, które podałeś:</p>

<table>
   <tr><th>Nazwa bazy danych:
      <td><?=$db->name?>
   <tr><th>Użytkownika bazy danych:
      <td><?=$db->user?>
   <tr><th>Hasło:
      <td><?=$db->pass?>
   <tr><th>Serwer:
      <td><?=$db->host?>
   <tr><th>Prefiks nazw tabel:
      <td><?=$db->prefix?>

   <tr><th>Twoja nazwa użytkownika:
      <td><?=$user->user?>
   <tr><th>Twoje hasło:
      <td><?=$user->pass?>

   <tr><th>Nazwa strony:
      <td><?=$site->siteName?>
</table>
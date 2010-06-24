<?php
ob_start();
if(file_exists('install_lock'))
{
   header('Location: locked.php');
   exit;
}
?>
<h2>Instalacja - krok 4</h2>

<div class="box_i">
<strong>Krok 4</strong>
W tym kroku zostanie skonfigurowana baza danych (import tabel)
</div>

<h3>Utworzenie bazy danych</h3>

<p>Jeśli baza danych, którą podałeś (pole <em>nazwa bazy danych</em>) nie istnieje,
należy ją utworzyć. Możesz to zrobić np. poprzez phpMyAdmin</p>

<h3>Import tabel</h3>

<p>Utworzyłeś bazę danych? Aby importować tabele do bazy danych podaj
jeszcze raz informacje o bazie danych (takie same jak w kroku drugim) i kliknij "Dalej".</p>

<form action="install5.php" method="post">
   <fieldset>
      <legend>Baza danych</legend>
      
      <label>Host bazy danych</label>
      <input name="dbHost" value="localhost">
      
      
      <label>Użytkownik bazy danych</label>
      <input name="dbUser">
      
      
      <label>Hasło bazy danych</label>
      <input name="dbPass" type="password">
      
      
      <label>Nazwa bazy danych</label>
      <input name="dbName">
   </fieldset>
   
   <div class="box_t">
   Zanim przejdziesz dalej, upewnij się czy na pewno podałeś poprawne dane.
   </div>
   
   <p>
      <input type="submit" value="Dalej" class="dc">
   </p>
</form> 

<big><a href="install2.php">Wstecz</a></big> (Krok 2)
<?php
   include 'layout.php';
?>
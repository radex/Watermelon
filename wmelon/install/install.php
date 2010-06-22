<?php
ob_start();
if(file_exists('install_lock'))
{
   header('Location: locked.php');
   exit;
}
?>
<h2>Instalacja - krok 1</h2>
<p>Witaj w instalatorze Watermelon CMS! Instalator przeprowadzi Ciebie przez pięć banalnych etapów konfiguracji.
   Instalacja powinna zająć jedynie kilka minut. Przed instalacją upewnij się, że znasz niezbędne
   dane - nazwa użytkownika bazy danych i hasło do bazy danych. Zanim przejdziesz dalej, zmień uprawnienia (tzw. chmody)
   pliku <em>config.php</em> na 777. Gotowy? Kliknij "Dalej" na dole strony :)</p>
   
<br><br>
   
<strong>Krok 1</strong><br>
Wstęp do instalacji

<br><br>

<strong>Krok 2</strong><br>
Wpisanie wszystkich danych niezbędnych do instalacji, m. in. haseł bazy danych i nazwy strony

<br><br>

<strong>Krok 3</strong><br>
Zapisywanie konfiguracji

<br><br>

<strong>Krok 4</strong><br>
Importowanie tabel do bazy danych

<br><br>

<strong>Krok 5</strong><br>
Uruchamianie Watermelon CMS

<br><br>

<div class="dr">
(Krok 2) <big><a href="install2.php">Dalej</a></big>
</div>
<?php
   include 'layout.php';
?>
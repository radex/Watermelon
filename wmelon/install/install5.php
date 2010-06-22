<?php
ob_start();
if(file_exists('install_lock'))
{
   header('Location: locked.php');
   exit;
}

$dbHost     = $_POST['dbHost'];
$dbUser     = $_POST['dbUser'];
$dbPass     = $_POST['dbPass'];
$dbName     = $_POST['dbName'];

mysql_connect($dbHost, $dbUser, $dbPass);
mysql_select_db($dbName);

$filename = 'sqldump.sql';
$handle = fopen($filename, 'rb');
$sqldump = fread($handle, filesize($filename));
fclose($handle);

$sqldump = explode('--****--', $sqldump);

foreach($sqldump as $query)
{
   mysql_query($query);
}

echo mysql_error();

?>
<h2>Instalacja - krok 5</h2>

<div class="box_i">
<strong>Krok 5</strong>
Podsumowanie instalacji, dodatkowe rady itp.
</div>

<p>Instalacja dobiegła już końca. Teraz możesz już przejść na stronę główną swojej strony.
W razie problemów wejdź na <a href="http://radex.i15.eu">radex.i15.eu</a>. Na stronie jest
już przykładowy użytkownik <em>radex</em> hasło <em>qwerty</em>, ale zadziała on tylko, gdy
podstawowym algorytmem haszowania jest <em>xsha256</em>. Po zakończonej instalacji zmień
uprawnienia (tzw. chmody) pliku <em>config.php</em> na 664 i stwórz plik <em>install_lock</em>
w katalogu <em>wtrmln/install/</em>. Utworzenie tego pliku jest bardzo ważne. Bez niego
każdy będzie mógł wejść w instalator i zepsuć konfigurację. Ewentualnie można usunąć katalog
<em>wtrmln/install/</em> . Miłego testowania!</p>

<big><a href="install4.php">Wstecz</a></big> (Krok 4)
<?php
   include 'layout.php';
?>
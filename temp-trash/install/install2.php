<?php
ob_start();
if(file_exists('install_lock'))
{
   header('Location: locked.php');
   exit;
}
?>
<h2>Instalacja - krok 2</h2>

<div class="box_i">
<strong>Krok 2</strong>
W tym kroku podasz wszystkie dane (takie jak hasło do bazy danych, nazwa strony) niezbędne do zainstalowania Watermelon CMS
</div>

<div class="box_t">
Jeśli nie jesteś doświadczym użytkownikiem, jeśli po raz pierwszy masz styczność z Watermelon CMS, proszę nie zmieniaj pól oznaczonych "tylko dla doświadczonych". Jeśli nie wiesz co robisz, możesz coś zepsuć i instalacja nie powiedzie się. Pamiętaj, że zawsze będziesz mógł zmienić konfigurację w Panelu Admina.
</div>

<p>Wypełnij wszystkie pola i kliknij "Dalej" pod formularzem, aby przejść dalej</p>

<form action="install3.php" method="post">
   <fieldset>
      <legend>Ścieżki</legend>
      
      <label>Główna ścieżka</label>
      
      <p>
         Główna ścieżka do Watermelon CMS-a, czyli gdzie będzie się znajdować strona
         <br>
         np. http://mojastrona.pl/
         <br>
         slash (ukośnik, "/") na końcu ścieżki jest <strong>bardzo ważny</strong>
      </p>
      
      <input name="baseURL" pattern=".*\/" title="Ścieżka musi się kończyć ukośnikiem." type="url" id="baseURL" value="http://mojastrona.pl/" onchange="document.getElementById('siteURL').value = document.getElementById('baseURL').value + 'index.php/'">
      
      
      
      
      <label>Ścieżka index.php <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Ścieżka do podstron, czyli główna ścieżka + index.php/
         <br>
         np. http://mojastrona.pl/index.php/
         <br>
         slash (ukośnik, "/") na końcu ścieżki jest bardzo ważny
         <br>
         Jeśli nic nie zmieniałeś w pliku .htaccess, to <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="siteURL" id="siteURL" value="http://mojastrona.pl/index.php/" type="url">
      
      
      
      <label>Główny folder <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Główny folder CMS-a
         <br>
         Jeśli chesz, aby główny folder CMS-a nie nazywał się wtrmln/, zmień jego nazwę, a następnie podaj w tym polu jego nową nazwę (wraz ze slashem na końcu)
         <br>
         Jeśli nie zależy Ci na zmianie nazwy folderu wtrmln/ to <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="cmsDir" value="wtrmln/">
      
   </fieldset>
   
   <fieldset>
      <legend>Baza danych</legend>
      
      <label>Host bazy danych</label>
      
      <p>
         Adres serwera bazy danych
         <br>
         na 99% będzie to <em>localhost</em>
         <br>
         Jeśli nie wiesz jaki jest Twój host bazy danych <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="dbHost" value="localhost">
      
      
      
      <label>Użytkownik bazy danych</label>
      
      <input name="dbUser">
      
      
      
      
      <label>Hasło bazy danych</label>
      
      <input name="dbPass" type="password">
      
      
      
      <label>Nazwa bazy danych</label>
      
      <p>
         Nazwa bazy danych w której ma się znajdować Watermelon CMS
         <br>
         Podana przez Ciebie nazwa bazy danych nie musi istnieć, stworzysz ją w kolejnym kroku.
      </p>
      
      <input name="dbName">
      
      
      
      <label>Prefiks tabel <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Prefiks nazw tabel w bazie danych
         <br>
         Jeśli chcesz, żeby prefiks był inny niż 'wcms_' musisz edytować nazwy tabel w wtrmln/install/sqldump.sql, a
         następnie podać w tym polu nowy prefiks
         <br>
         Jeśli nie zależy Ci na zmianie prefiksu, lub/i plik sqldump.sql nie został edytowany <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="dbPrefix" value="wcms_">
      
   </fieldset>
   
   <fieldset>
      <legend>Pozostałe</legend>
      
      <label>Nazwa strony</label>
      
      <p>
         np. Strona Jasia Śmietany
      </p>
      
      <input name="siteName">
      
      
      
      <label>Slogan strony</label>
      
      <p>
         Krótki opis strony, wyświetlany w większości skórek pod nazwą strony
         <br>
         np. Śmieszne filmiki, muzyka, obrazki...
      </p>
      
      <input name="siteSlogan">
      
      
      
      <label>Layout <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Nazwa layoutu (skórki, wyglądu strony)
         <br>
         Jeśli nie zależy Ci na zmianie domyślnego wyglądu, lub/i nie zostały wgrane dodatkowe layouty <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="theme" value="wcmslay">
      
      
      
      <label>Domyślny kontroler <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Nazwa domyślnego kontrolera
         <br>
         Jeśli nie wiesz co to jest kontroler, lub/i znasz nazw kontrolerów <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="defaultCnt" value="test">
      
      
      
      <label>Algorytmy haszowania <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Lista nazw algorytmów haszowania zapisanych według nazewnictwa algorytmów haszowania Watermelon CMS-a. Nazwy powinny być zawarte w apostrofach i oddzielone przecinkiem
         <br>
         np. '_sha1','_md5','xsha256'
         <br>
         Jeśli nie znasz nazw algorytmów haszowania, lub/i nie zależy Ci na ich zmianie <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="hashAlgo" list="hashAlgos" value="'_sha1'">
      <datalist id="hashAlgos">
         <option label="sha1 (php)" value="'_sha1'"></option>
         <option label="md5 (php)" value="'_md5'"></option>
<?php
         
         if(function_exists('hash_algos'))
         {
            $hashAlgos = hash_algos();
            
            foreach($hashAlgos as $var)
            {
               echo '         <option label="' . $var . ' (hash)" value="\'x' . $var . '\'">' . "\n";
            }
         }
         ?>
      </datalist>
      
      
      
      <label>Domyślny algorytm haszowania <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Numer domyślnego algorytmu haszowania (liczony od zera)
         <br>
         np. jeśli lista algorytmów haszowania była ustawiona na <samp>'_sha1','_md5','xsha256'</samp> , a numer domyślnego algorytmu haszowania został ustawiony na 1 to domyślnym algorytmem haszowania będzie '_md5'
         <br>
         Jeśli nie jesteś pewien co robisz <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <input name="dHashAlgo" type="number" value="0" min="0" step="1">
      
      
      
      <label>Autoload <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Lista pluginów do wczytania w formie takiej jak w configu - array('nazwa', 'toEval'). Poszczególne pluginy oddzielaj enterem.
         <br>
         Jeśli nie jesteś pewien co robisz <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <textarea name="autoload" rows="10" cols="30">array('user', '')</textarea>
      
      
      
      <label>&lt;head&gt; <em>(tylko dla doświadczonych)</em></label>
      
      <p>
         Lista elementów (tagów), które mają być w &lt;head&gt;, np. słowa kluczowe, ustawienia DublinCore itp. 
         <br>
         Jeśli nie chcesz nic dodać sekcji &lt;head&gt; <strong>nie zmieniaj tego pola!</strong>
      </p>
      
      <textarea name="metaSrc" rows="10" cols="30"></textarea>
   </fieldset>
   
   <div class="box_t">
   Zanim przejdziesz dalej, upewnij się czy na pewno podałeś poprawne dane.
   </div>
   
   <p>
      <input type="submit" value="Dalej" class="dc">
   </p>
</form> 

<big><a href="install.php">Wstecz</a></big> (Krok 1)
<?php
   include 'layout.php';
?>
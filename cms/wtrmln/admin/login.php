<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License 
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

/*

Ten plik został napisany w sposób strukturalny, tak więc dodupnie. Trzeba to
przeportować do klasy koniecznie. 

*/

include 'commons.php';
if(AreLoggedIn()) header("Location: index.php");

//$_SESSION['WTRMLN_ADMIN_STARTTIME'] = time();

// dodaje jeden do liczby błędnych logowań.
function addFail($_db, $failData, $failsRows)
{
   if($failsRows == 0)
   {
      $_db->query("INSERT INTO `admin_loginfail` (`ip`,`fails`,`lastfail`) VALUES ('" . ClientIP() . "','1','" . time() . "')");
   }
   else
   {
      if($failData->fails == '1')
      {
         $_db->query("UPDATE `admin_loginfail` SET `fails` = '2', `lastfail` = '" . time() . "' WHERE `ip` = '" . ClientIP() . "'");
      }
      else
      {
         $_db->query("UPDATE `admin_loginfail` SET `fails` = '3', `lastfail` = '" . time() . "' WHERE `ip` = '" . ClientIP() . "'");
      }
   }
}


if($_GET['check'] == 'true')
{
   $checkFail = $_db->query("SELECT `fails`,`lastfail` FROM `admin_loginfail` WHERE `ip` = '" . ClientIP() . "'");
   $failData = $checkFail->to_obj();
   $failsRows = $checkFail->num_rows();
   
   //sprawdzamy czy nasz IP ma w ciągu godziny 3 faile
   if($failsRows > 0)
   {
      
      if($failData->fails == '3')
      {
         if($failData->lastfail > (time()-3600))
         {
            $_C = '<h1>Błąd</h1>Przekroczono limit trzech błędnych logowań na godzinę.';
            include 'themes/' . WTRMLN_ADMIN_THEME . '/login.php';
            die;
         }
         else
         {
            //jeśli są jakieś faile, ale przedawnione, usuń wiersz
            $_db->query("DELETE FROM `admin_loginfail` WHERE `ip` = '" . ClientIP() . "'");
         }   
      }
   }
   
   // sprawdzamy dane
   
   $formNick = $_POST['nick'];
   $formPass = $_POST['pass'];
   
   if(empty($formNick) || empty($formPass))
   {
      $_C = '<h1>Błąd</h1>Nie wszystkie pola zostały wypełnione.<br>'.
            '<div id="label">&nbsp;</div><input type="button" value="Cofnij" onClick="history.back()">';
      include 'themes/' . WTRMLN_ADMIN_THEME . '/login.php';
      die;
   }
   
   // istnienie admina
   
   if(!isset($_w_superusers[$formNick]))
   {
      addFail(&$_db, &$failData, &$failsRows);
      $_C = '<h1>Błąd</h1>Taki administrator nie istnieje.<br>'.
            '<div id="label">&nbsp;</div><input type="button" value="Cofnij" onClick="history.back()">';
      include 'themes/' . WTRMLN_ADMIN_THEME . '/login.php';
      die;
   }
   
   // hasło
   
   if(strHash($formPass) != $_w_superusers[$formNick])
   {
      addFail(&$_db, &$failData, &$failsRows);
      $_C = '<h1>Błąd</h1>Niepoprawne hasło.<br>'.
           '<div id="label">&nbsp;</div><input type="button" value="Cofnij" onClick="history.back()">';
      include 'themes/' . WTRMLN_ADMIN_THEME . '/login.php';
      die;
   }
   
   // ok. Zalogowany.
   
   $_SESSION['WTRMLN_ADMIN_LOGIN'] = $formNick;
   $_SESSION['WTRMLN_ADMIN_PASS'] = $_w_superusers[$formNick];
   $_SESSION['WTRMLN_ADMIN_LOGGED_IN'] = 'true';
   
   $_C = '<h1>Zalogowany!</h1>'.
         'Witaj <strong>' . $formNick . '</strong>!<br>'.
         '<div id="label">&nbsp;</div><input type="button" value="Przejdź" onClick="window.location = \'index.php\'">';
   
   // zalogowaliśmy się, tak więc wszystkie faile są już nieważne
   $_db->query("DELETE FROM `admin_loginfail` WHERE `ip` = '" . ClientIP() . "'");
   
}
else
{
   $_C = '<h1>Logowanie</h1>'.
         '<label for="nick">Login:</label><input type="text"     name="nick" id="nick"><br>'.
         '<label for="nick">Hasło:</label><input type="password" name="pass" id="pass"><br>'.
         '<div id="label">&nbsp;</div>    <input type="submit"   value="Loguj"        ><br>'.
         '<div id="label">&nbsp;</div>    <input type="button"   value="Powróć do strony"  '.
         'onClick="document.location = \'' . WTRMLN_SITEURL . '\'">';
}

include 'themes/' . WTRMLN_ADMIN_THEME . '/login.php';
?>

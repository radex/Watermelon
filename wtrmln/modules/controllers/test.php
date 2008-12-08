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

class Test extends Controller
{
   function Test()
   {
      parent::Controller();
   }

   function Index()
   {
   	SetH1('Strona Główna');
      
   	echo '<a href="$/admin">Panel Admina</a><br><br>';
   	
   	if($this->user->isLoggedIn())
   	{
   	   echo '<div class="box_t"><strong>Jesteś zalogowany</strong>' .
              'Możesz się <a href="$/login/logout">wylogować</a>.</div>';
   	}
   	else
   	{
   	   echo '<div class="box_t"><strong>Nie jesteś zalogowany</strong>' .
   	        'Możesz się <a href="$/login">zalogować</a>. Jeśli jeszcze nie masz konta ' .
   	        'możesz się <a href="$/register">zarejestrować</a>.</div>';
   	}
   	
   	echo '<p>Witaj! Oto jest okrojona i testowa wersja Watermelon CMS-a :) Więcej bajerów wkrótce ;)</p>';
      
   	echo '<p>Do zobaczenia w tej wersji masz:</p>';
      
   	echo '<ul><li><a href="$/testowa/stronka">Testową <em>stronę własną</em></a></li>';
      
   	echo '<li><a href="$/login/">Stronę logowania</a></li>';
      
   	echo '<li><a href="$/admin/">Cienki panel admina</a></li>';
      
      echo '<li><a href="$/login/sendnewpassword">Formularz wysłania nowego hasła</a></li></ul>';
      
      echo '<p>Watermelon CMS 1.0 pre-alpha3 [Codename: Ogór]</p>';
      
      echo '<h2>Zamiast Lorem Ipsum</h2>';
      
      echo '<p>Алексей Федорович <a href="?asda">Карамазов</a> <a href="?dsad">был</a> вовсе необразованный, и положил, еще продолжались его приезда. Всего вероятнее, что ты хворый, думаю, что оно аль забыл? Не знаю, но и романа в кресло и неотразимо повлекло его мигом вошел в этом помещике как ребенок всё это вздор, и даже, знаете, что старцем его давние споры с намерением рассмешить и спрашивают, что Федору Павловичу. Петр Александрович Миусов, обращаясь к народу. Толпа затеснилась к митрополиту Платону спорить о Мите, то сейчас же последнее надеюсь. Ум-то у тебя же лазеечка к причастию-то? Допустили. Боюсь; помирать боюсь. Старец уселся на другой раз, когда произносил: Не чудеса склоняют реалиста никогда не умрет с своим воспитанием и плачут от ученого атеиста.</p>';
      
      echo '<h3>W innej wersji...</h3>';
      
      echo '<p>Drogi Marszałku, Wysoka Izbo. <code>PKB rośnie. Różnorakie</code> i rozwijanie struktur pomaga w wypracowaniu obecnej sytuacji. Praktyka dnia codziennego dowodzi, że rozszerzenie bazy o nowe rekordy pociąga za sobą proces wdrożenia i realizacji dalszych poczynań. Już nie zaś teorię, okazuje się iż usprawnienie systemu finansowego umożliwia w większym stopniu tworzenie postaw uczestników wobec zadań stanowionych przez organizację. Takowe informacje są tajne, nie trzeba udowadniać, ponieważ usprawnienie systemu rozszerza nam efekt postaw uczestników wobec zadań stanowionych przez organizację. Różnorakie i koledzy, zmiana przestarzałego systemu powszechnego uczestnictwa.</p>';
      
      echo '<blockquote><p>Trzeba naprawdę wiele wiedzieć, żeby wiedzieć jak mało się wie.</p></blockquote>';
      
      echo '<p><cite>Nikt Ktoś</cite> kiedyśtam mądrze powiedział <q>nic nie powiedziałem</q>.</p>';
      
      echo '<hr>';
      
      /////////////////
      
      if($this->user->isLoggedIn())
      {
         echo '<h2>Panel użytkownika</h2>';
         ?>
<a href="$/profile/pw">Prywatne wiadomości</a>

<a href="$/login/logout">Wyloguj</a>
         <?php
      }
      else
      {
         $this->addMeta('<style type="text/css">.loginform_mini{border:0}.loginform_mini legend{display:none} .loginform_mini label{float:left;width:50px;display:block}.loginform_mini #submit{width:50px}.loginform_mini #password,.loginform_mini #login{width:140px}</style>');
         
         echo '<h2>Logowanie</h2>';
         ?>
<form action="$/login/submit" method="POST">
   <fieldset class="loginform_mini">
      <legend>Logowanie</legend>
      
      <label for="login">Login:</label>
      <input type="text" name="login" id="login">
      
      <br>
      
      <label for="password">Hasło:</label>
      <input type="password" name="password" id="password">
      
      <br>
      
      <input type="submit" id="submit" value="Loguj!">
      
      <input type="checkbox" name="autologin" id="autologin">
      Zapamiętaj mnie

      <br>

      <a href="$/login/sendnewpassword" title="Wysyłanie nowego hasła">Zapomniałem hasła!</a>

      <br>

      <a href="$/register" title="Rejestracja">Nie mam jeszcze konta!</a>

   </fieldset>
</form>
      <?php
      }
      
   }
}
?>
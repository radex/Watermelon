<?php
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

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

define('PAGEINDEX', '');

class Test extends Controller
{
   function Index()
   {
      /* piaskownica 3 */
      
      
      
      /* piaskownica 2 */
      
      ob_start();
      ?>
[b color="red"]test[/b]

[quote=andrew]
   [quote=radex]
      blabla
   [/quote]
blablba
[/quote]

[php]
$test = 'blabla';
echo $test;
$test = "test ${test} etste";
[/php]
      <?php
      $content = ob_get_contents();
      ob_end_clean();
      
      //Benchmark::start('bbcode_off');
      echo bbcode($content);
      //Benchmark::end('bbcode_off');
      
      //Benchmark::start('bbcode_cached');
      echo bbcode_cached($content);
      //Benchmark::end('bbcode_cached');
      
      
      return;
      
      /* piaskownica */
      
      Watermelon::$acceptMessages[] = 'login_success';
      
      var_dump(URL::$segments, URL::$class, URL::$message, URL::$method);
      
      echo 'tutaj będzie przykładowy blok<br><br>';
      
      echo $this->load->block('test');
      
      echo $this->load->block('koniecszkoły');
      
      /* stare testy */
      
   	SetH1('Strona Główna');
   	
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
   	
   	//echo '<div class="dr"><img src="http://upload.wikimedia.org/wikipedia/commons/d/d6/Watermelon.svg" alt="arbuz z wiki" style="width:200px;height:200px;"><br>Arbuz z <a href="http://commons.wikimedia.org/">Wikimedia Commons</a></div>';
   	
   	echo '<p>Witaj w Watermelon CMS!</p>';
   	
   	echo '<p><a href="http://en.wikipedia.org/wiki/Watermelon">Trochę o watermelonach</a></p>';
      
   	echo '<p>Rzeczy do zobaczenia:</p>';
      
   	echo '<ul><li><a href="$/testowa/stronka">Testowa <em>strona własna</em></a></li>';
      
   	echo '<li><a href="$/login/">Strona logowania</a></li>';
      
      echo '<li><a href="$/login/sendnewpassword">Formularz wysłania nowego hasła</a></li></ul>';
      
      echo '<p>Watermelon CMS 1.0 pre-alpha4</p>';
      echo '<h2>Zamiast Lorem Ipsum</h2>';
      
      echo '<p>Алексей Федорович <a href="?asda">Карамазов</a> <a href="?dsad">был</a> вовсе необразованный, и положил, еще продолжались его приезда. Всего вероятнее, что ты хворый, думаю, что оно аль забыл? Не знаю, но и романа в кресло и неотразимо повлекло его мигом вошел в этом помещике как ребенок всё это вздор, и даже, знаете, что старцем его давние споры с намерением рассмешить и спрашивают, что Федору Павловичу. Петр Александрович Миусов, обращаясь к народу. Толпа затеснилась к митрополиту Платону спорить о Мите, то сейчас же последнее надеюсь. Ум-то у тебя же лазеечка к причастию-то? Допустили. Боюсь; помирать боюсь. Старец уселся на другой раз, когда произносил: Не чудеса склоняют реалиста никогда не умрет с своим воспитанием и плачут от ученого атеиста.</p>';
      
      echo '<h3>W innej wersji...</h3>';
      
      echo '<p>Drogi Marszałku, Wysoka Izbo. <code>PKB rośnie. Różnorakie</code> i rozwijanie struktur pomaga w wypracowaniu obecnej sytuacji. Praktyka dnia codziennego dowodzi, że rozszerzenie bazy o nowe rekordy pociąga za sobą proces wdrożenia i realizacji dalszych poczynań. <q>Już nie zaś teorię, okazuje</q> się iż usprawnienie systemu finansowego umożliwia w większym stopniu tworzenie postaw uczestników wobec zadań stanowionych przez organizację. Takowe informacje są tajne, nie trzeba udowadniać, ponieważ usprawnienie systemu rozszerza nam efekt postaw uczestników wobec zadań stanowionych przez organizację. Różnorakie i koledzy, zmiana przestarzałego systemu powszechnego uczestnictwa.</p>';
      
      echo '<blockquote><p>Trzeba naprawdę wiele wiedzieć, żeby wiedzieć jak mało się wie.</p></blockquote>';
      
      echo '<h2>Boksy używane w Watermelonie</h2>';
      
      echo '<div class="box_e"><strong>Błąd</strong>czerwony</div>';
      echo '<div class="box_w"><strong>Ostrzeżenie</strong>pomarańczowy</div>';
      echo '<div class="box_t"><strong>Podpowiedź</strong>szary (ew. jasnoszarożółty w niektórych skinach)</div>';
      echo '<div class="box_c"><strong>Gotowe, wykonano</strong>jasnozielony</div>';
      echo '<div class="box_q"><strong>Pytanie</strong>błękitny</div>';
      echo '<div class="box_i"><strong>Informacja</strong>niebieski</div>';
      
      echo '<div class="box_q"><form action="" method="post"><fieldset><legend>Pytanie zaawansowane</legend><p>bla, bla, bla, bla, bla, bla-bla Bla, bla Bla bla. Bla, bla Bla?</p><input type="button" value="tak" class="ans_yes"><input type="button" value="nie" class="ans_no"></fieldset></form></div>';
      
      echo plDate(mktime(5,7,58,12,15,1978));
   }
   
   function phpinfo()
   {
      phpinfo();
   }
   
   function clearcache()
   {
      Cache::ClearCache();
   }
}
?>
<?php if(!defined('WM_IS')) exit;
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

// ładujemy biblioteki

include WM_LIBS . 'libs.php';

// ładujemy helpery

include WM_HELPERS . 'helpers.php';

/*
 * function panic(string $text = 'noname error')
 * 
 * Zatrzymanie wykonywania CMS-a. Coś jak exit, czy die,
 * ale zawsze jest jedynym widocznym tekstem w przeglądarce,
 * co jest użyteczne w przypadku Output Bufferingu. Opis
 * błędu jest widoczny tylko, gdy stała DEBUG jest zdefiniowana.
 * 
 * string $text - opis błędu.
 */

function panic($text = 'noname error')
{
   exit('<div style="position: absolute;
        z-index: 999;
        top: 0;
        left: 0;
        background: #fff;
        width: 100%;
        height: 100%;">
        <big>Błąd krytyczny uniemożliwiający kontynuowanie.</big>' . (defined('DEBUG') ? '<br>' . $text : '') . '</div>');
}

########################################
#           class Watermelon           #
########################################

class Watermelon
{
   /*
    * public static array $PA_Events
    * 
    * tablica wydarzeń dodawanych prosto z 
    * kontrolerów i samego Watermelona.
    * 
    * Każdy taki event jest w postaci tablicy
    * o wartościach:
    * 
    *    array(string $link, int $int, string $description)
    *    
    * gdzie:
    * 
    *    string $link - podstrona, na którą ma zostać
    *                   przeniesiony user po kliknięciu
    *                   (jeśli nie-string, to event nie
    *                   będzie linkiem)
    *    int    $int  - liczba, np. "6" w wyrażeniu
    *                   "6 nowych komentarzy". (jeśli
    *                   nie-int, to event nie będzie
    *                   miał boksu na liczbę)
    *    string $description - opis, np. "nowych
    *                    komentarzy w wyrażeniu
    *                    "6 nowych komentarzy" (wymagane)
    * 
    * np:
    * 
    * Watermelon::$PA_Events[] = array('comments', 6, 'nowych komentarzy');
    * 
    * wyświetli u góry event:
    * 
    * [6] Nowych komentarzy
    * 
    * a kliknięcie na niego przeniesie na podstronę "comments"
    */
   
   public static $PA_Events = array();
   
   /*
    * public static string[] $metaSrc
    *
    * dane meta (tagi z sekcji <head>)
    *
    * $metaSrc = array(string $head_element[, $head_element[, ... ]])
    *   $head_element - pojedynczy element do umieszczenia w sekcji <head>
    */
   public static $metaSrc = array();
   
   /*
    * public static object $pcdb
    * 
    * dane klasy PluginsConfigDatabase
    * 
    * [jak będzie ktoś miał czas i chęci to trzeba to przenieść do klasy Plugins
    * ConfigDatabase. To nie powinno być tutaj]
    */
   
   public static $pcdb = null;
   
   /*
    * public static string[] $acceptMessages
    * 
    * akceptowane wiadomości dla danej podstrony.
    * Aby dodać taką akceptowaną wiadomość, wystarczy
    * w kontrolerze dodać do tej tablicy element, np:
    * 
    * Watermelon::$acceptMessages[] = 'login_success';
    * 
    * Można także dodawać tutaj wiadomości, które mają
    * być zawsze akceptowane
    */
   
   public static $acceptMessages = array();

   /*
    * public void Watermelon(string $dbHost,   string $dbUser,   string   $dbPass, string $dbName,
    *                        string $dbPrefix, array  $autoload, string[] $metaSrc)
    *
    * Konstuktor. Odpala najważniejsze biblioteki, odpowiedni kontroler
    * i generuje stronę.
    *
    * string   $dbHost   - host bazy danych
    * string   $dbUser   - użytkownik bazy danych
    * string   $dbPass   - hasło do bazy danych
    * string   $dbName   - nazwa bazy danych
    * string   $dbPrefix - prefiks do tabel
    * array    $autoload - pluginy i kod związany z nimi do automatycznego załadowania
    * string[] $metaSrc  - dane do wstawienia w sekcji <head>
    *
    * $autoload = array(array(string $plugin_name, string $eval)[, array(string $plugin_name, string $eval)[, ... ]]
    *   $plugin_name - nazwa plugina
    *   $eval        - związany z tym pluginem kod do wykonania
    *
    * $metaSrc = array(string $head_element[, $head_element[, ... ]])
    *   $head_element - pojedynczy element do umieszczenia w sekcji <head>
    */

   public function Watermelon($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix, array $autoload, array $metaSrc)
   {
      $url = new URL(Config::$defaultController);
      $db  = new DB();
      $db->connect($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
      
      $this->LoadPlugins($autoload);
      
      self::$metaSrc = $metaSrc;
      
      $content = $this->loadController();
      
      $this->generatePage($content);
   }
   
   /*
    * public static void addmsgs(string $msg[, string $msg[, ... ]])
    * 
    * dodaje do Watermelon::$acceptMessages
    * wiadomości (każda wiadomość to jeden argument)
    */
   
   public static function addmsgs()
   {
      $messages = func_get_args();
      
      foreach($messages as $msg)
      {
         self::$acceptMessages[] = $msg;
      }
   }

   /*
    * private void loadController()
    *
    * ładuje odpowiedni kontroler (wykonuje pracę Front Controllera)
    */

   private function loadController()
   {
      // zamieniamy _ na /, tak aby można było robić kontrolery w podfolderach
      // (przydatne, kiedy mamy moduł składający się z kilku kontrolerów, np.
      // duży skrypt forum)

      $controllerPath = str_replace('_', '/', URL::$class);

      $controllerPath = WM_CONTROLLERS . $controllerPath . '.php';

      // sprawdzanie, czy istnieje plik controllera

      if(file_exists($controllerPath))
      {
         include $controllerPath;
         
         // wywalamy dwa pierwsze segmenty URL-a (kontroler i jego funkcja składowa/metoda)
         
         array_shift(URL::$segments);
         array_shift(URL::$segments);
      }
      else
      {
         //jeśli nie można znaleźć kontrolera, odpalamy e404
         include WM_CONTROLLERS . 'e404.php';
         
         $controller = new e404();
         
         URL::$method = 'index';
         URL::$class  = 'e404';
      }
      
      // sprawdzanie, czy istnieje klasa controllera

      if(class_exists(URL::$class))
      {
         $controller = new URL::$class();
      }
      else
      {
         panic('Nie moge znalesc klasy podanego controllera (' . URL::$class . ')');
      }
      
      // nie można wywołać __* (czyli np. __call, __construct)
      
      if(substr(URL::$method, 0, 2) == '__')
      {
         URL::$method = 'index';
      }
      
      // sprawdzanie czy istnieje dana funkcja składowa controllera.
      
      if(!method_exists($controller, URL::$method))
      {
         if(method_exists($controller, '__call'))
         {
            $controller->__call(URL::$method, array());
            
            $content = ob_get_contents(); //wyciagamy dane z bufora wyjścia
            @ob_end_clean();
            
            return $content;
         }
         else
         {
            panic('Nie moge znalesc podanej funkcji składowej controllera (' . URL::$method . ')');
         }
      }

      // przystepujemy do roboty

      $controller->{URL::$method}();
      
      $content = ob_get_contents(); //wyciagamy dane z bufora wyjścia
      @ob_end_clean();
      
      return $content;
   }

   /*
    * private void LoadPlugins(array $plugins)
    *
    * Ładuje pluginy i wykonuje kod związany z tymi pluginami
    *
    * $plugins = array(array(string $plugin_name, string $eval)[, array(string $plugin_name, string $eval)[, ... ]]
    *   $plugin_name - nazwa plugina
    *   $eval        - związany z tym pluginem kod do wykonania
    */

   private function LoadPlugins(array $plugins)
   {
      foreach($plugins as $plugin)
      {
         list($plugin_name, $eval) = $plugin;

         if(file_exists(WM_PLUGINS . $plugin_name . '.php'))
         {
            include(WM_PLUGINS . $plugin_name . '.php');

            eval($eval);
         }
      }
   }

   /*
    * private void generatePage(string $content)
    *
    * ostatecznie generuje stronę (dodaje znaczniki do head, oczyszcza treść itd.)
    *
    * string $content - treść wygenerowana przez kontroler
    */

   private function generatePage($content)
   {
      // umożliwiamy w prosty sposób tworzenie ścieżek do podstron

      $content = str_replace('href="$/', 'href="' . WM_SITEURL, $content);
      $content = str_replace('action="$/', 'action="' . WM_SITEURL, $content);
      
      // preparujemy wiadomość
      
      if(is_string(URL::$message))
      {
         if(in_array(URL::$message, self::$acceptMessages))
         {
            $_w_message = Loader::view(URL::$message);
         }
      }

      // preparujemy zawartość <title> :)

      $siteTitle = (defined('WM_H1') ? WM_H1 . ' &raquo; ' : '') . WM_SITENAME;
      
      array_unshift(Watermelon::$metaSrc, '<title>' . $siteTitle . '</title>');
      
      $_w_content = $content;

      // odpalamy skina

      include WM_THEMEPATH . 'skin.php';
   }
   
   public static function getMeta($tab = '   ')
   {
      $metaSrc = Watermelon::$metaSrc;
      
      foreach($metaSrc as $metaItem)
      {
         $meta .= $tab . $metaItem . "\n";
      }
      
      return $meta;
   }
}

new Watermelon($_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix, $_w_autoload, $_w_metaSrc);

// dla bezpieczeństwa usuwamy dane konfiguracji bazy danych

unset($_w_dbHost);
unset($_w_dbUser);
unset($_w_dbPass);
unset($_w_dbName);
unset($_w_dbPrefix);

?>

<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
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

if(!defined('WM_IS')) exit;

session_start();
session_regenerate_id();

ob_start();

header('Content-Type: text/html; charset=UTF-8');

// fixing "magic" quotes

if(get_magic_quotes_gpc())
{
   function stripslashes_deep($value)
   {
      $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
      return $value;
   }
   $_POST    = array_map('stripslashes_deep', $_POST);
   $_GET     = array_map('stripslashes_deep', $_GET);
   $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
   $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

// setting proper error reporting modes, and debug constants in respect to internal debug level constant

switch(WM_DEBUGLEVEL)
{
   case'0':
   default:
      error_reporting(0);
      break;
   case '1':
      error_reporting(E_ALL ^ E_NOTICE);
      define('WM_DEBUG', '');
      break;
   case '2':
      error_reporting(E_ALL);
      define('WM_DEBUG', '');
      break;
}

// constants

define(    'WM_SITEURL', $_w_siteURL                         );
define(     'WM_PUBURL', $_w_baseURL  . $_w_publicDir   . '/');
define('WM_UPLOADEDURL', $_w_baseURL  . $_w_uploadedDir . '/');

define(    'WM_CMSPATH', $_w_basePath . $_w_cmsDir      . '/');
define(    'WM_PUBPATH', $_w_basePath . $_w_publicDir   . '/');

define(       'WM_LIBS', WM_CMSPATH . 'libs/'                );
define(    'WM_HELPERS', WM_CMSPATH . 'helpers/'             );

// loading libraries and helpers

include WM_LIBS    . 'libs.php';
include WM_HELPERS . 'helpers.php';

// config

Config::$hashAlgo = $_w_hashAlgo;
Config::setSuperusers($_w_superusers);

// main CMS class

class Watermelon
{
   /*
    * public static string[] $headData
    *
    * tags from <head> section
    *
    * $headData = string $headTag[]
    *   $headTag - individual element to put in <head> section
    */
   public static $headData = array();
   
   /*
    * private static string[] $acceptMessages
    * 
    * akceptowane wiadomości dla danej podstrony.
    * Wiadomości dodaje się poprzez Watermelon::addmsgs(), np:
    * 
    * Watermelon::addmsgs('login_success', 'login_loggedout');
    */
   
   //private static $acceptMessages = array('login_success', 'login_loggedout');
   
   /*
    * public void Watermelon(string $dbHost,   string $dbUser,   string   $dbPass, string $dbName,
    *                        string $dbPrefix, array  $autoload, string[] $headData)
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
    * string[] $headData - dane do wstawienia w sekcji <head>
    *
    * $autoload = array(array(string $plugin_name, string $eval)[, array(string $plugin_name, string $eval)[, ... ]]
    *   $plugin_name - nazwa plugina
    *   $eval        - związany z tym pluginem kod do wykonania
    *
    * $metaSrc = array(string $head_element[, $head_element[, ... ]])
    *   $head_element - pojedynczy element do umieszczenia w sekcji <head>
    */

   public function Watermelon($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix, array $autoload, array $headData)
   {
      $url = new URL(Config::$defaultController);
      
      DB::connect($dbHost, $dbUser, $dbPass, $dbName, $dbPrefix);
      
      //$this->LoadPlugins($autoload);
      
      self::$headData = $headData;
      
      //$content = $this->loadController();
      
      //$this->generatePage($content);
   }
   
   /*
    * public static void addmsgs(string $msg[, string $msg[, ... ]])
    * 
    * adds messages to Watermelon::$acceptMessages
    * (one argument is one message)
    */
   /*
   public static function addmsgs()
   {
      $messages = func_get_args();
      
      foreach($messages as $msg)
      {
         self::$acceptMessages[] = $msg;
      }
   }*/
   
   // TODO: It has to be completely rewritten.
   /*
    * private void loadController()
    *
    * loads proper controller (makes the Front Controller work)
    *//*

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
         //jeśli nie można znaleźć kontrolera, niech Pages przejmie stery
         include WM_CONTROLLERS . 'pages.php';
         
         $controller = new pages();
         
         URL::$method = 'index';
         URL::$class  = 'pages';
         
         // tutaj pewien trik (wiem, że brzydko to rozwiązałem, ale ważne, że 
         // działa :p). Po prostu jeśli URL::$onesegment jest prawdziwe, skraca
         // ilość segmentów URL-a do jednego. Ma to uchronić przed traktowaniem 
         // adresu np. /test/ jako /test/index ...
         
         if(URL::$onesegment === TRUE)
         {
            URL::$segments = URL::$segments[0];
         }
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
      
   }*/
   
   // It's not relevant for the moment
   

   /*
    * private void LoadPlugins(array $plugins)
    *
    * Ładuje pluginy i wykonuje kod związany z tymi pluginami
    *
    * $plugins = array(array(string $plugin_name, string $eval)[, array(string $plugin_name, string $eval)[, ... ]]
    *   $plugin_name - nazwa plugina
    *   $eval        - związany z tym pluginem kod do wykonania
    *//*

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
   }*/
   
   /*
    * private void generatePage(string $content)
    *
    * finally generates a page (adds tags into <head>, cleans content, etc)
    *
    * string $content - content generated by controller
    */
   
   private function generatePage($content)
   {
      // enabling to make links to subpages in simple way
      
      $content = str_replace('href="$/',   'href="'   . WM_SITEURL, $content);
      $content = str_replace('action="$/', 'action="' . WM_SITEURL, $content);
      /*
      // preparujemy wiadomość
      
      if(is_string(URL::$message))
      {
         if(in_array(URL::$message, self::$acceptMessages))
         {
            $_w_message = Loader::view(URL::$message);
         }
      }
*/
      // preparing page's title
      
      $siteTitle = (defined('WM_H1') ? WM_H1 . ' &raquo; ' : '') . WM_SITENAME;
      
      array_unshift(self::$metaSrc, '<title>' . $siteTitle . '</title>');
      /*
      $_w_content = $content;
      
      // odpalamy skina
      
      include WM_THEMEPATH . 'skin.php';*/
   }
   
   /*
    * public static string getMeta(string $tab = '   ')
    * 
    * zwraca zawartość <head>
    * używaj tego przy tworzeniu layoutów
    * domyślnie rozdziela elementy znakiem nowej linii "\n"
    * i dodaje na początku każdego wcięcie o wielkości trzech
    * spacji (jeśli ma być inny - podaj jaki w paramentrze $tab)
    * 
    * string $tab - wygląd wcięcia
    */
   /*
   public static function getMeta($tab = '   ')
   {
      $metaSrc = Watermelon::$metaSrc;
      
      foreach($metaSrc as $metaItem)
      {
         $meta .= $tab . $metaItem . "\n";
      }
      
      return $meta;
   }*/
}

/***/


include 'prototypes/Registry/proto.php';

exit;

/***/

new Watermelon($_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix, $_w_autoload, $_w_metaSrc); // TODO: use some other DB parameters passing method - for security reasons - in case of uncaught exception full trace of invoked functions is shown, revealing passwords.

// dla bezpieczeństwa usuwamy dane konfiguracji bazy danych

unset($_w_dbHost);
unset($_w_dbUser);
unset($_w_dbPass);
unset($_w_dbName);
unset($_w_dbPrefix);

?>

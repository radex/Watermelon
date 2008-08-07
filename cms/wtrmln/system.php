<?php if(!defined('WTRMLN_IS')) die;
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

header("Content-Type: text/html; charset=UTF-8");

########################################
#             Biblioteki               #
########################################

include(WTRMLN_LIBS . 'url.php');
include(WTRMLN_LIBS . 'db.php');
include(WTRMLN_LIBS . 'loader.php');
include(WTRMLN_LIBS . 'plugins_handle.php');
include(WTRMLN_LIBS . 'controller.php');
include(WTRMLN_LIBS . 'model.php');
include(WTRMLN_LIBS . 'plugin.php');

include(WTRMLN_HELPERS . 'helpers.php');

////////////////////////////////////////

/* panika cms'a.
/* zamiast die i exit w przypadku poważnego błędu.
************************/

function panic($text = 'noname error')
{
   die('<div style="position: absolute;
        z-index: 999;
        top: 0;
        left: 0;
        background: #fff;
        width: 100%;
        height: 100%;">
        <big>Błąd krytyczny uniemożliwiający kontynuowanie.</big><br>Debug: ' . $text . '</div>');
}

class Watermelon
{
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

      $controllerPath = WTRMLN_CONTROLLERS . $controllerPath . '.php';

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
         include WTRMLN_CONTROLLERS . 'pages.php';

         $controller = new pages();

         URL::$method = 'index';
         URL::$class  = 'pages';
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
      
      // sprawdzanie czy istnieje dana funkcja składowa controllera.
      
      if(!method_exists($controller, URL::$method))
      {
         panic('Nie moge znalesc podanej funkcji składowej controllera (' . URL::$method . ')');
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

         if(file_exists(WTRMLN_PLUGINS . $plugin_name . '.php'))
         {
            include(WTRMLN_PLUGINS . $plugin_name . '.php');

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

      $content = str_replace('href="$/', 'href="' . WTRMLN_SITEURL, $content);
      $content = str_replace('action="$/', 'action="' . WTRMLN_SITEURL, $content);

      // preparujemy zawartość <title> :)

      $siteTitle = (defined('WTRMLN_H1') ? WTRMLN_H1 . ' &raquo; ' : '') . WTRMLN_SITENAME;

      // wyciągamy metaSrc

      $metaSrc = self::$metaSrc;

      // żeby array_unshift się nie czepiał,
      // gdyby wcześniej nie było żadnych elementów

      if(!$metaSrc)
      {
         $metaSrc = array();
      }

      // wsadzamy na początek tablicy <title>

      array_unshift($metaSrc, '<title>' . $siteTitle . '</title>');

      // zmieniamy nazwy, żeby skin.php wiedział odzochodzi

      $_w_metaSrc = $metaSrc;
      $_w_content = $content;

      // odpalamy skina

      include WTRMLN_THEMEPATH . 'skin.php';
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

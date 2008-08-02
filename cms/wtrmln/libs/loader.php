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

/*
 * Lib Loader
 * wersja 1.3.5
 * 
 * Ładowanie różnego rodzaju modułów
 * 
 */ 

include WTRMLN_LIBS . 'viewtags.php';

class Loader
{
   /* 
    * public string view(string $view[, array $vars[, bool $panicifne = true]])
    * 
    * Pobiera i zwraca zawartość viewa
    *
    * string $view - nazwa szablonu
    * array  $vars - przesyłane dane
    * bool   $panicifne [panic if not exists] -
    *          Gdy pliku nie ma na serwerze:
    *            Jeśli true - wywalenie błędu krytycznego
    *            Jeśli false - zwrócenie FALSE
    * 
    * $vars = array($varname => string $var[, $varname => string $var[, ... ]])
    *   $varname - nazwa zmiennej do zamiany
    *   $var     - treść zmiennej o nazwie $varname
    */
   
   public function view($view, array $vars = array(), $panicifne = true)
   {
      $view = str_replace('_', '/', $view);
      $path_theme = WTRMLN_THEMEPATH . 'views/' . $view . '.php';
      $path = WTRMLN_VIEWS . $view . '.php';
      
      // sprawdzamy czy istnieje theme view
      
      if(file_exists($path_theme))
      {
         $path = $path_theme;
      }

      // sprawdzamy czy view istnieje
         
      if(!file_exists($path))
      {
         if($panicifne)
         {
            panic('Lib Loader: 0');
         }
         else
         {
            return false;
         }
      }

      ob_start();
      
      // zmienne
      
      foreach($vars as $varname => $var)
      {
         $$varname = $var;
      }
      
      //długa nazwa, w razie gdyby ktoś chciał w viewie takiej użyć
      
      $___Loader_Path = $path;
      
      //usuwanie niepotrzebnych zmiennych
      
      unset($vars);
      unset($view);
      unset($path);
      unset($path_theme);
      
      $___Loader_toEval = file_get_contents($___Loader_Path);
      $___Loader_toEval = ViewTags::Process($___Loader_toEval);
      echo eval('?>' . $___Loader_toEval . '<?php ');
      
      //oprónienie bufora
      
      $buffer = ob_get_contents();
      @ob_end_clean();
      return $buffer;
   }
   
   /* 
    * public Model model(string $modelName[, bool $panicifne = true])
    * 
    * Zwraca obiekt modelu
    * 
    * string $model - nazwa modelu do wczytania
    * bool   $panicifne [panic if not exists] -
    *          Gdy pliku nie ma na serwerze:
    *            Jeśli true - wywalenie błędu krytycznego
    *            Jeśli false - zwrócenie false
    */
   
   public function model($model, $panicifne = true)
   {
      $model = strtolower($model);
      
      $model = 'model_' . $model;
      
      $path = WTRMLN_MODELS . $model . '.php';
      
      if(!file_exists($path))
      {
         if($panicifne)
         {
            panic('Lib Loader: 1');
         }
         else
         {
            return FALSE;
         }
      }
      
      include $path;
      
      $model_object = new $model();
      
      return $model_object;
   }
}


?>

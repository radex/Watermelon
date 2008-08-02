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
 * Eksperymentalna klasa do tworzenia tabelek
 *
 */

class PATables
{
   private $vars;

   private static function paTableCaption()
   {
      static $captions;
      if(!$captions)
      {
         $captions = true;
         return '<caption>';
      }
      else
      {
         return '</caption>';
      }
   }

   private function paTableFindvar($varname)
   {
   	// trochę zakręcone: interesująca nas nazwa jest w pierwszym [1]
   	// elemencie $varname, zamieniamy ją na małe litery, i wyszukujemy
   	// w $this->vars element o tej właśnie nazwie (strtolower($varname[1]))

      return $this->vars[strtolower($varname[1])];
   }

   function paTable($struct, $vars)
   {
   	$patable = $struct;

   	//zamiana na małe litery i przeniesienie do zmiennej składowej
   	foreach($vars as $var)
   	{
   		$var = strtolower($var);
   	}
   	$this->vars = $vars;

      // tworzenie nagłówka tabeli
      $patable = preg_replace_callback('#==#', array($this, 'paTableCaption'), $patable);

      // zmienne
      // thx for Crozin (forum.php.pl)
      $patable = preg_replace_callback('/\$\(([a-zA-Z0-9]+)\)/', array($this, 'paTableFindvar'), $patable);

      $patable = str_replace(array("\r\n", "\r"), "\n", $patable);

      $patable = explode("\n", $patable);

      foreach($patable as $patabl)
      {
         $patable_ .= $patabl . '<br>';
      }
      $patable = $patable_;

      return '<table>' . $patable . '</table>';
   }

   ///

   private static function paTableTrs()
   {
      static $secondline;
      if(!$secondline)
      {
         $secondline = true;
         return '<tr class="secondline"><td>';
      }
      else
      {
         $secondline = false;
         return '<tr><td>';
      }
   }
   /*
   function paTable($struct, $vars)
   {
      $patable = $struct;

      $varname = NULL;
      $tovar = FALSE;

      for($i = 0; $i < strlen($patable); $i++)
      {

         if($patable[$i] == '%')
         {
            $tovar = TRUE;
            continue;
         }

         if($tovar == TRUE)
         {
            if(ord($patable[$i]) > 97 && ord($patable[$i]) < 122)
            {
               $varname .= $patable[$i];
            }
            else
            {
               $tovar = FALSE;
               $patable2 .= $vars[$varname];
               $varname = NULL;
               $patable2 .= $patable[$i];
            }
         }
         else
         {
            if($patable[$i] != '%')
            {
               $patable2 .= $patable[$i];
            }
         }

      }

      $patable = $patable2;

      $patable = str_replace('{:', '<tr><th>', $patable);
      $patable = str_replace(':}', '</th></tr>', $patable);
      $patable = str_replace(':',  '</th><th>', $patable);

      //$patable = str_replace('{', $this->paTableTr(), $patable);
      $patable = preg_replace_callback('#\{#', array($this, 'paTableTr'), $patable);
      $patable = str_replace('}', '</td></tr>', $patable);
      $patable = str_replace('|', '</td><td>', $patable);

      $patable = '<div><table>' . $patable . '</table></div>';

      return $patable;
   }*/
}
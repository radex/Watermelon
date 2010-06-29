<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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

/*
 * Lib Cache
 * wersja 1.1.0
 * 
 * Cache'owanie
 * 
 */

class Cache
{
   /*
    * public static void CacheView(string $name, string $content)
    * 
    * cache'uje widok o nazwie $name z treścią $content
    * 
    * string $name    - nazwa widoku, wraz z folderem i końcówką .php
    *                   np. 'pw/pwlist.php'
    * string $content - treść widoku do zacache'owania
    */
   
   public static function CacheView($name, $content)
   {
      if(strpos($name, '/') === false)
      {
         $fp = fopen(WM_CACHE . 'views/'  . $name, 'w');
         fwrite($fp, $content);
         fclose($fp);
      }
      else
      {
         $name = explode('/', $name);
         $dir = $name[0];
         $name = $name[1];
         
         if(!file_exists(WM_CACHE . 'views/' . $dir))
         {
            mkdir(WM_CACHE . 'views/' . $dir);
         }
         
         $fp = fopen(WM_CACHE . 'views/' . $dir . '/' . $name, 'w');
         fwrite($fp, $content);
         fclose($fp);
      }
   }
   
   /*
    * public static string GetView(string $name)
    * 
    * zwraca zacache'owany widok o nazwie $name
    * zwraca false gdy widok wcale nie został zacache'owany
    * 
    * string $name    - nazwa widoku, wraz z folderem i końcówką .php
    *                   np. 'pw/pwlist.php'
    */
   
   public static function GetView($name)
   {
      $name = explode('/', $name);
      $dir = $name[0];
      $name = $name[1];
      
      $path = WM_CACHE . 'views/' . $dir . '/' . $name;
      
      if(!file_exists($path))
      {
         return false;
      }
      
      return file_get_contents($path);
   }
   
   /*
    * public static void CacheBBCode(string $bbcode, string $parsed)
    * 
    * cache'uje fragment treści bbcode
    * 
    * string $bbcode - fragment bbcode do zacache'owania
    * string $parsed - ten sam fragment, ale po parsowaniu
    */
   
   public static function CacheBBCode($bbcode, $parsed)
   {
      $bbcodehash = strHash($bbcode);
      
      $fp = fopen(WM_CACHE . 'bbcode/' . $bbcodehash . '.php', 'w');
      fwrite($fp, '<?php exit; ?>' . $parsed);
      fclose($fp);
   }
   
   /*
    * public static string GetBBCode(string $bbcode)
    * 
    * pobiera i zwraca sparsowaną formę BBCode'u
    * o treści $bbcode
    */
   
   public static function GetBBCode($bbcode)
   {
      $bbcodehash = strHash($bbcode);
      
      $path = WM_CACHE . 'bbcode/' . $bbcodehash . '.php';
      
      if(!file_exists($path))
      {
         return false;
      }
      
      return substr(file_get_contents($path), 14);
   }
   
   /*
    * public static void ClearCache()
    * 
    * czyści cache
    */
   
   public static function ClearCache()
   {
      foreach(new DirectoryIterator(WM_CACHE . 'views/') as $_res)
      {
         if($_res->isDot())
         {
            unset($_res);
            continue;
         } 
           
         if($_res->isFile())
         {
            self::removeResource($_res->getPathName());
         }
         elseif($_res->isDir())
         {
            self::removeResource($_res->getRealPath());
         }
         
         unset($_res);
      }
      
      foreach(new DirectoryIterator(WM_CACHE . 'bbcode/') as $_res)
      {
         if($_res->isDot())
         {
            unset($_res);
            continue;
         } 
           
         if($_res->isFile())
         {
            self::removeResource($_res->getPathName());
         }
         elseif($_res->isDir())
         {
            self::removeResource($_res->getRealPath());
         }
         
         unset($_res);
      }
   }
   
   /*
    * private static void removeResource(string $_target)
    * 
    * niszczy zasób (folder lub plik)
    * 
    * based on http://pl.php.net/manual/pl/function.rmdir.php#86112
    * 
    * string $_target - ścieżka zasobu do usunięcia
    */
   
   private static function removeResource($_target)
   {
      if(is_file($_target))
      {
         if(is_writable($_target))
         {
            if(@unlink($_target))
            {
               return true;
            }
         }
         
         return false;
      }
      
      if(is_dir($_target))
      {
         if(is_writeable($_target))
         {
            foreach(new DirectoryIterator($_target) as $_res)
            {
               if($_res->isDot())
               {
                  unset($_res);
                  continue;
               }
               
               if($_res->isFile())
               {
                  self::removeResource($_res->getPathName());
               }
               elseif($_res->isDir())
               {
                  self::removeResource($_res->getRealPath());
               }
               
               unset($_res);
            }
             
            if(@rmdir($_target))
            {
               return true;
            }
         }
         
         return false;
      } 
   }
}

?>

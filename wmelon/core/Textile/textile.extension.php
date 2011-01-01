<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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
 * Textile [A Humane Web Text Generator] extension
 */

/*
future ideas:

- make it extensible by other extensions
- and (as an example) make LaTeX extension (that'd be cool, huh?)
- move it to universal Cache, when it's done

*/

include 'textile.php';

class Textile_Extension extends Extension
{
   private static $textile;         // instance of Textile_Lib
   private static $shelf = array();
   
   /*
    * public static string textile(string $textile)
    * 
    * Converts Textile markup to HTML
    */
   
   public static function textile($text)
   {
      // fetching cached or generating HTML from Textile
      
      $hash = md5($text);
      
      $path = WM_Cache . 'textile/' . $hash . '.php';
      
      if(!file_exists($path) || defined('WM_Debug'))
      {
         // Textile --> HTML
         
         $textiled = self::generateTextile($text);
         
         // array --> string to be cached
         
         $cacheString = "<?php\ndefined('WM') or die;\n"; // so that direct access is not possible
         
         $cacheString .= '$textiled = ' . var_export($textiled, true) . ';';
         
         // caching
         
         if(!defined('WM_Debug'))
         {
            file_put_contents($path, $cacheString);
         }
      }
      else
      {
         include $path;    // textiled content is in $textiled variable
      }
      
      // generating final output
      
      foreach($textiled as $i => $data)
      {
         if($i % 2 == 0)
         {
            // if $data is string
            
            $output .= $data;
         }
         else
         {
            // if $data is PHP code to be evaluated
            
            ob_start();
            
            $evaluated  = eval($data);
            $evaluated .= ob_get_clean();
            
            $output .= $evaluated;
         }
      }
      
      return $output;
   }
   
   /*
    * public static string textileRestricted(string $textile)
    * 
    * Converts restricted Textile markup to HTML
    * 
    * Use for comments, forum posts and similar
    */
   
   public static function textileRestricted($text)
   {
      // fetching cached or generating HTML from Textile
      
      $hash = md5($text);
      
      $path = WM_Cache . 'textile_restricted/' . $hash . '.php';
      
      if(!file_exists($path) || defined('WM_Debug'))
      {
         // generating & caching
         
         $textiled = self::$textile->TextileRestricted($text);
         
         if(!defined('WM_Debug'))
         {
            file_put_contents($path, '<?php die?>' . $textiled); // so that direct access is not possible
         }
         
         return $textiled;
      }
      else
      {
         $textiled = file_get_contents($path);
         $textiled = substr($textiled, 11);      // removing <?php die? >
         
         return $textiled;
      }
   }
   
   /**************************************************************************/
   
   /*
    * Does actual convertion from Textile markup to HTML
    */
   
   private static function generateTextile($text)
   {
      // shelving code snippets & PHP to be executed
      
      $text = preg_replace_callback('/<code\(([^)]+)\)>(.*?)<\/code>/ms', array(__CLASS__, 'shelveCode'), $text);
      $text = preg_replace_callback('/<exec>(.*?)<\/exec>/ms', array(__CLASS__, 'shelveExec'), $text);
      
      // replacing youtube tag
      
      $text = preg_replace_callback('/youtube\.(.*?)v=([0-9a-zA-Z_-]+)(.*?)$/s', array(__CLASS__, 'replaceYoutube'), $text);
      
      // textile
      
      $text = self::$textile->TextileThis($text);
      
      // fixing relative links
      
      $text = preg_replace_callback('/href="([^"]+)"/', array(__CLASS__, 'fixLinks'), $text);
      
      // unshelving
      
      foreach(self::$shelf as $i => $item)
      {
         $text = str_replace('<wm:shelf(' . $i . ')>', $item, $text);
      }
      
      // converting to array (needed for <exec>)
      
      $text = explode('<wm:execStop/>', $text);
      
      return $text;
   }
   
   /*
    * (pseudo-)constructor
    */
   
   public static function onAutoload()
   {
      self::$textile = new Textile_Lib;
      
      // once every 1000 views, clearing Textile cache
      
      if(mt_rand(0, 1000) == 0)
      {
         $items1 = FilesForDirectory(WM_Cache . 'textile/');
         $items2 = FilesForDirectory(WM_Cache . 'textile_restricted/');
         
         foreach(array_merge($items1, $items2) as $path)
         {
            if(substr($path, -4) == '.php') // checking for extensions, so that .gitignore won't be deleted
            {
               unlink($path);
            }
         }
      }
   }
   
   /*
    * replaces <code()>..</code> syntax with placeholder (and puts HTML on "shelf"), so that entities won't be broken by Textile
    */
   
   private static function shelveCode($args)
   {
      list(, $brush, $code) = $args;
      
      $shelvedID = count(self::$shelf);
      
      self::$shelf[$shelvedID] = '<pre class="brush: ' . $brush . '">' . htmlspecialchars($code, ENT_NOQUOTES) . '</pre>';
      
      return ' <wm:shelf(' . $shelvedID . ')>';
   }
   
   /*
    * replaces <exec>..</exec> syntax with placeholder (and puts PHP from it on "shelf")
    */
   
   private static function shelveExec($args)
   {
      list(, $code) = $args;
      
      $shelvedID = count(self::$shelf);
      
      self::$shelf[$shelvedID] = '<wm:execStop/>' . $code . '<wm:execStop/>';
            // <wm:execStop/>s will be used later to convert converted text to array (so that when cached, text and code to be evaluated are separated)
      
      return '<wm:shelf(' . $shelvedID . ')>';
   }
   
   /*
    * replaces 'youtube. [url]' syntax with HTML
    */
   
   private static function replaceYoutube($args)
   {
      $videoID = $args[2];
      
      // return '<iframe class="youtubeVideo" src="http://youtube.com/embed/' . $videoID . '"></iframe>';
      
      return '<object class="youtubeVideo">' .
      '  <param name="movie" value="http://www.youtube-nocookie.com/v/' . $videoID . '?fs=1"></param>' .
      '  <param name="allowFullScreen" value="true"></param>' .
      '  <param name="allowscriptaccess" value="always"></param>' .
      '  <embed src="http://www.youtube-nocookie.com/v/' . $videoID . '?fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" class="youtubeVideo"></embed>' .
      '</object>';
   }
   
   /*
    * adds website base URL to links that does not start with 'http(s)://'
    */
   
   private static function fixLinks($args)
   {
      list($href, $url) = $args;
      
      if(substr($url, 0, 7) == 'http://')
      {
         return $href;
      }
      elseif(substr($url, 0, 8) == 'https://')
      {
         return $href;
      }
      else
      {
         return 'href="' . WM_SiteURL . $url . '"';
      }
   }
}

// Textile_Extension is used instead of just Textile, so that use of method textile() is possible

class Textile extends Textile_Extension{}
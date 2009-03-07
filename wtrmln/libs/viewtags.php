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

/*
 * Lib ViewTags
 * wersja 2.0.1
 * 
 * Przetwarzanie pseudo-tagów używanych m. in. w widokach.
 */

class ViewTags
{
   /*
    * public static string process(string $data)
    * 
    * Funkcja dostaje na wejściu dotychczasową treść widoku i zwraca ją po
    * przetworzeniu (obsługa pseudo-tagów)
    * 
    * string $data - dotychczasowa treść widoku
    */
   
   public static function process($data)
   {
      // w razie, gdyby nie można było używać <? i <?=
      
      $data = str_replace('<? ', '<?php ', $data);
      $data = str_replace('<?=', '<?php echo ', $data);
      
      // umożliwiamy w prosty sposób tworzenie ścieżek do podstron
      
      $data = str_replace('href="$/', 'href="' . WTRMLN_SITEURL, $data);
      $data = str_replace('action="$/', 'action="' . WTRMLN_SITEURL, $data);
      
      // tag variable
      
      $data = preg_replace_callback('#<\$([a-zA-Z0-9_]+)>#', array('ViewTags', 'tag_variable'), $data);
      
      // tag foreach
      
      $data = preg_replace_callback('#<foreach ([^>]+)>#', array('ViewTags', 'tag_foreach'), $data);
      $data = preg_replace("#</foreach>#", "<?php } ?>", $data);
      
      // tag if
      
      $data = preg_replace_callback('#<if \(([^)]+)\)>#', array('ViewTags', 'tag_if'), $data);
      $data = preg_replace_callback('#<if ([^>]+)>#', array('ViewTags', 'tag_if'), $data);
      $data = preg_replace('#</if>#', '<?php } ?>', $data);
      
      // tag while
      
      $data = preg_replace_callback('#<while \(([^)]+)\)>#', array('ViewTags', 'tag_while'), $data);
      $data = preg_replace_callback('#<while ([^>]+)>#', array('ViewTags', 'tag_while'), $data);
      $data = preg_replace('#</while>#', '<?php } ?>', $data);
      
      // tag else
      
      $data = preg_replace('#<else>#', '<?php else{ ?>', $data);
      $data = preg_replace('#</else>#', '<?php } ?>', $data);
      
      $data = preg_replace('#<\?php \} \?>[\s]+<\?php else\{ \?>#', '<?php }else{ ?>', $data);
      
      // tag end
      
      /*$data = preg_replace('#<end>#', '<?php } ?>', $data);*/ //deprecated
      
      // tag nick
      
      //$data = preg_replace_callback('#<nick \$([a-zA-Z0-9_]+)>#', array('ViewTags', 'tag_nick'), $data);
      $data = preg_replace('#<nick \$([a-zA-Z0-9_]+)>#', '<?php echo User::getNick($\\1); ?>', $data);
      
      // tag date
      
      //$data = preg_replace_callback('#<date \$([a-zA-Z0-9_]+)>#', array('ViewTags', 'tag_date'), $data);
      $data = preg_replace('#<date \$([a-zA-Z0-9_]+)>#', '<?php echo plDate($\\1); ?>', $data);
      
      // tag unpack
      
      $data = preg_replace('#<unpack \$([a-zA-Z0-9_]+)>#', '<?php foreach($\\1 as $key => $var){$$key = $var;} ?>', $data);
      
      // tag list object
      
      $data = preg_replace('#<list object \$([a-zA-Z0-9_]+)>#', '<?php while($\\1_item = $\\1->to_obj()){foreach($\\1_item as $key => $var){$$key = $var;} ?>', $data);
      $data = preg_replace('#</list>#', '<?php } ?>', $data);
      
      // tag load page
      
      $data = preg_replace_callback('#<load page ([^>]+)>#', array('ViewTags', 'tag_load_page'), $data);
      
      // tag block
      
      $data = preg_replace('#<block ([^>]+)>#', '<?php echo Loader::Block(\'\\1\'); ?>', $data);
      
      return $data;
   }

   /*
    * private static string tag_variable(string[2] $data)
    * 
    * obsługuje "wyświetlanie zmiennych" tzn. zamienia przykładowe <$nazwa_zmiennej>
    * na <?php echo $nazwa_zmiennej; ?>
    * Nazwa zmiennej jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */

   private static function tag_variable($data)
   {
      return '<?php echo $' . $data[1] . '; ?>';
   }
   
   /*
    * private static string tag_nick(string[2] $data)
    * 
    * obsługuje pobieranie nicku tzn. zamienia przykładowe <nick $uid>
    * na <?php echo User::getNick($uid); ?>
    * UID jest brany z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */

   private static function tag_nick($data)
   {
      return '<?php echo User::getNick($' . $data[1] . '); ?>';
   }
   
   /*
    * private static string tag_date(string[2] $data)
    * 
    * obsługuje wyświetlanie polskich dat tzn. zamienia przykładowe <date $created>
    * na <?php echo plDate($created); ?>
    * timestamp jest brany z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */

   private static function tag_date($data)
   {
      return '<?php echo plDate($' . $data[1] . '); ?>';
   }

   /*
    * private static string tag_foreach(string[2] $data)
    * 
    * obsługuje foreach tzn. zamienia przykładowe <foreach $foo as $bar>
    * na <?php foreach($foo as $bar){ ?>
    * Zawartość foreach (np. "$foo as $bar") jest brana z drugiego elementu $data.
    * Pierwszy jest niewykorzystywany. Istnieje on ze względu na sposób działania
    * funkcji preg_replace_callback.
    */

   private static function tag_foreach($data)
   {
      return '<?php foreach(' . $data[1] . '){ ?>';
   }

   /*
    * private static string tag_if(string[2] $data)
    * 
    * obsługuje warunki tzn. zamienia przykładowe <if 4 == 5 - 1>
    * na <?php if(4 == 5 - 1){ ?>
    * Treść warunku jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */

   private static function tag_if($data)
   {
      return '<?php if(' . $data[1] . '){ ?>';
   }
   
   /*
    * private static string tag_while(string[2] $data)
    * 
    * obsługuje warunki tzn. zamienia przykładowe <while 4 == 5 - 1>
    * na <?php while(4 == 5 - 1){ ?>
    * Treść warunku jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */

   private static function tag_while($data)
   {
      return '<?php while(' . $data[1] . '){ ?>';
   }
   
   /*
    * private static string tag_load_page(string[2] $data)
    * 
    * obsługuje ładowanie stron tzn. zamienia przykładowe <load page testowa/stronka>
    * na treść strony testowa/stronka
    * Nazwa strony jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */
   
   private static function tag_load_page($data)
   {
      $load = new Loader();
      $pages = $load->model('pages');
      
      $pagedata = $pages->GetData($data[1])->to_obj();
      $pagedata = ViewTags::Process($pagedata->content);
      
      ob_start();
      $page = eval('?>' . $pagedata . '<?php ');
      $page = ob_get_contents();
      @ob_end_clean();
      
      return $page;
   }
   
   /*
    * private static int rand(int $flag) // testowa
    * 
    * losuje liczbę i ją zwraca, gdy $flag == 0
    * lub zwraca wylosowaną już liczbę gdy $flag != 0  
    */
   
   private static $randed;
   
   private static function rand($flag)
   {
      if($flag == 0)
      {
         self::$randed = mt_rand();
         return self::$randed;
      }
      else
      {
         return self::$randed;
      }
   }
}

?>
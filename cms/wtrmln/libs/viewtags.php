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
 * Lib ViewTags
 * wersja 1.1.4
 * 
 * Przetwarzanie pseudo-tagów używanych w widokach.
 */ 

class ViewTags
{
   /*
    * public string process(string $data)
    * 
    * Funkcja dostaje na wejściu dotychczasową treść widoku i zwraca ją po
    * przetworzeniu (obsługa pseudo-tagów)
    * 
    * string $data - dotychczasowa treść widoku
    */
   public function process($data)
   {
      $data = str_replace('<?=', '<?php echo ', $data);
      
      $data = preg_replace_callback('#<\$([a-zA-Z0-9_]+)>#', array('ViewTags', 'tag_variable'), $data);
      
      $data = preg_replace_callback('#<foreach ([^>]+)>#', array('ViewTags', 'tag_foreach'), $data);
      $data = preg_replace("#</foreach>#", "<?php } ?>", $data);
      
      $data = preg_replace_callback('#<if ([^>]+)>#', array('ViewTags', 'tag_if'), $data);
      $data = preg_replace("#</if>#", "<?php } ?>", $data);
      
      $data = preg_replace('#<end>#', '<?php } ?>', $data);
      
      return $data;
   }
   
   /*
    * private string tag_variable(string[2] $data)
    * 
    * obsługuje "wyświetlanie zmiennych" tzn. zamienia przykładowe <$nazwa_zmiennej>
    * na <?php echo $nazwa_zmiennej; ?>
    * Nazwa zmiennej jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */
   
   private function tag_variable($data)
   {
      return '<?php echo $' . $data[1] . '; ?>';
   }
   
   /*
    * private string tag_foreach(string[2] $data)
    * 
    * obsługuje foreach tzn. zamienia przykładowe <foreach $foo as $bar>
    * na <?php foreach($foo as $bar){ ?>
    * Zawartość foreach (np. "$foo as $bar") jest brana z drugiego elementu $data.
    * Pierwszy jest niewykorzystywany. Istnieje on ze względu na sposób działania
    * funkcji preg_replace_callback.
    */
   
   private function tag_foreach($data)
   {
      return '<?php foreach(' . $data[1] . '){ ?>';
   }
   
   /*
    * private string tag_if(string[2] $data)
    * 
    * obsługuje warunki tzn. zamienia przykładowe <if 4 == 5 - 1>
    * na <?php if(4 == 5 - 1){ ?>
    * Treść warunku jest brana z drugiego elementu $data. Pierwszy jest
    * niewykorzystywany. Istnieje on ze względu na sposób działania funkcji
    * preg_replace_callback.
    */
   
   private function tag_if($data)
   {
      return '<?php if(' . $data[1] . '){ ?>';
   }
}

?>
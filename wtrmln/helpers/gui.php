<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * class GUI
 * 
 * tworzenie graficznego interfejsu użytkownika
 * (np. boksy takie jak: informacja, pytanie, błąd)
 */

class GUI
{
   /*
    * public static string questionBox(string $question, string $direct[, string $noJS = 'history.back()'[, string $header = 'Pytanie']])
    * 
    * tworzy pytanie.
    * 
    * string $question - pytanie, które chcemy zadać użytkownikowi
    * string $direct   - link, do którego user ma zostać odesłaniu po kliknięciu "tak".
    * string $noJS     - JavaScript, który ma zostać odpalony po kliknięciu "nie".
    *                    domyślnie cofa na poprzednią stronę: history.back()
    * string $header   - nagłówek boksa. domyślnie 'Pytanie'.
    */
   
   public static function questionBox($question, $direct, $noJS = 'history.back()', $header = 'Pytanie')
   {
      $gui  = '<div class="box_q">';
      $gui .= '<form action="' . $direct . '" method="post">';
      $gui .= '<fieldset>';
      $gui .= '<legend>' . $header . '</legend>';
      $gui .= '<p>' . $question . '</p>';
      $gui .= '<input type="submit" value="tak" class="ans_yes">';
      $gui .= '<input type="button" value="nie" class="ans_no" onclick="' . $noJS . '">';
      $gui .= '</fieldset>';
      $gui .= '</form>';
      $gui .= '</div>';
      
      return $gui;
   }
   
   /*
    * public static string simpleQuestionBox(string $text[, string $header = 'Pytanie'])
    * 
    * tworzy proste pytanie (bez przycisków "tak", "nie").
    * 
    * string $text   - treść boksa (pytania)
    * string $header - nagłówek boksa. domyślnie 'Pytanie'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function simpleQuestionBox($text, $header = 'Pytanie')
   {
      return self::renderBox($text, $header, 'q');
   }
   
   /*
    * public static string errorBox(string $text[, string $header = 'Błąd'])
    * 
    * wyświetla box błędu
    * 
    * string $text   - treść boksa (błędu)
    * string $header - nagłówek boksa. domyślnie 'Błąd'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function errorBox($text, $header = 'Błąd')
   {
      return self::renderBox($text, $header, 'e');
   }
   
   /*
    * public static string warningBox(string $text[, string $header = 'Ostrzeżenie'])
    * 
    * wyświetla box ostrzeżenia
    * 
    * string $text   - treść boksa (ostrzeżenia)
    * string $header - nagłówek boksa. domyślnie 'Ostrzeżenie'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function warningBox($text, $header = 'Ostrzeżenie')
   {
      return self::renderBox($text, $header, 'w');
   }
   
   /*
    * public static string tipBox(string $text[, string $header = 'Podpowiedź'])
    * 
    * wyświetla box podpowiedzi
    * 
    * string $text   - treść boksa (podpowiedzi)
    * string $header - nagłówek boksa. domyślnie 'Podpowiedź'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function tipBox($text, $header = 'Podpowiedź')
   {
      return self::renderBox($text, $header, 't');
   }
   
   /*
    * public static string doneBox(string $text[, string $header = 'Wykonano'])
    * 
    * wyświetla box wykonanej akcji
    * 
    * string $text   - treść boksa (opis wykonanej akcji)
    * string $header - nagłówek boksa. domyślnie 'Wykonano'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function doneBox($text, $header = 'Wykonano')
   {
      return self::renderBox($text, $header, 'c');
   }
   
   /*
    * public static string infoBox(string $text[, string $header = 'Informacja'])
    * 
    * wyświetla box informacji
    * 
    * string $text   - treść boksa (informacji)
    * string $header - nagłówek boksa. domyślnie 'Informacja'. jeśli wartość nie 
    *                  jest stringiem (może to być np. null), nie wyświetla nagłówka
    */
   
   public static function infoBox($text, $header = 'Informacja')
   {
      return self::renderBox($text, $header, 'i');
   }
   
   /*
    * private static string renderBox(string $text, string $header, string $type)
    * 
    * tworzy box klasy box_% gdzie % to $text, o treści $text i z nagłówkiem $header
    * 
    * string $text   - treść boksa
    * string $header - nagłówek boksa. jeśli wartość nie jest stringiem
    *                  (może to być np. null), nie wyświetla nagłówka
    * string $type   - klasa CSS (typ) boksa, np. jeśli $type = 'q', to box
    *                  będzie miał klasę box_q
    */
   
   private static function renderBox($text, $header, $type)
   {
      $gui = '<div class="box_' . $type . '">';
      
      if(is_string($header))
      {
         $gui .= '<strong>' . $header . '</strong>';
      }
      
      $text = str_replace('<strong>', '<strong class="inline">', $text);
      
      $gui .= $text;
      $gui .= '</div>';
      
      return $gui;
   }
}

?>
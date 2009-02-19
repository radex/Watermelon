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

class Model_TempKeys extends Model
{
   /*
    * public string[2] MakeKey(string $comment[, uint $time])
    * 
    * tworzy losowy klucz z komentarzem $comment,
    * zapisuje go w bazie i zwraca użytkownikowi.
    * Taki klucz ma żywotność 5 minut, lub wygasa
    * $time + 5 minut, gdy $time jest podane
    * 
    * zwraca: array(string $key, string $value)
    * przy czym $key jest to klucz zapisany w bazie
    * danych, a $value to wartość przypisana do tego klucza
    * 
    * string $comment - komentarz do klucza
    * uint   $time    - timestamp żywotności klucza. gdy
    *                   podany, klucz wygaśnie 300 sekund
    *                   później niż $time
    * 
    * przykład użycia:
    * 
    * $this->TempKeys = $this->load->model('tempkeys');
    * list($key, $value) = $this->TempKeys->MakeKey('komentarz');
    */
   
   public function MakeKey($comment, $time = null)
   {
      // generujemy losowy, ośmioznakowy klucz oraz wartość tego klucza
      
      $key = substr(strHash(uniqid(mt_rand(), true)), 0, 8);
      $value = substr(strHash(uniqid(mt_rand(), true)), 0, 8);
      $comment = mysql_real_escape_string($comment);
      
      // ustawiamy czas utworzenia
      
      if($time === null)
      {
         $time = time();
      }
      
      // zapisujemy klucz w bazie
      
      $this->db->query("INSERT INTO `__temporary_keys` (`key`, `value`, `created`, `comment`) VALUES ('%1', '%2', '%3', '%4')", $key, $value, $time, $comment);
      
      // usuwamy stare klucze
      
      $this->DeleteOldKeys();
      
      return array($key, $value);
   }
   
   /*
    * public object GetKey(string $key)
    * 
    * pobiera dane klucza tymczasowego o identyfikatorze $key
    * zwraca dane tego klucza, jeśli istnieje, lub false
    * gdy nie istnieje.
    */
   
   public function GetKey($key)
   {
      $key = mysql_real_escape_string($key);
      
      // pobieramy dane danego klucza
      
      $keyData = $this->db->query("SELECT * FROM `__temporary_keys` WHERE `key` = '%1'", $key);
      
      // jesli ten klucz nie istnieje
      
      if($keyData->num_rows() == 0)
      {
         return false;
      }
      
      // jeśli natomiast istnieje
      
      return $keyData->to_obj();
   }
   
   /*
    * public bool CheckKey(string $key, string $value, string $comment)
    * 
    * sprawdza, czy klucz jest prawdziwy (czy istnieje, czy wartość się
    * zgadza z tą w bazie danych oraz czy komentarz się zgadza z tym w
    * bazie danych).
    * 
    * zwraca true, jeśli klucz istnieje, false gdy jest fałszywy (nie
    * istnieje, wartość jest zła, lub komentarz jest zły)
    * 
    * string $key     - klucz, którego prawdziwość ma zostać potwierdzona
    * string $value   - wartość, która ma zostać porównana z tą w bazie danych
    * string $comment - komentarz, który ma zostać porównany z tym w bazie danych
    * 
    * przykład użycia:
    * 
    * $this->TempKeys = $this->load->model('tempkeys');
    * if(!$this->TempKeys->CheckKey($key, $value, $comment))
    * {
    *    echo $this->load->view('error');
    *    return;
    * }
    */
   
   public function CheckKey($key, $value, $comment)
   {
      // pobieramy dane
      
      $data = $this->GetKey($key);
      
      // sprawdzamy, czy takowy istnieje
      
      if(!$data)
      {
         return false;
      }
      
      // sprawdzamy czy wartości się zgadzają
      
      if($value != $data->value)
      {
         return false;
      }
      
      // sprawdzamy czy komentarze pasują do siebie
      
      if($comment != $data->comment)
      {
         return false;
      }
      
      // skoro wszystko się zgadza to usuwamy klucz
      
      $this->DeleteKey($key);
      
      return true;
   }
   
   /*
    * public void DeleteKey(string $key)
    * 
    * usuwa klucz tymczasowy o identyfikatorze $key
    */
   
   public function DeleteKey($key)
   {
      $key = mysql_real_escape_string($key);
      
      $this->db->query("DELETE FROM `__temporary_keys` WHERE `key` = '%1'", $key);
   }
   
   /*
    * private void DeleteOldKeys()
    * 
    * usuwa klucze, które zostały stworzone dawniej, niż 5 minut temu.
    * Ma to zapobiec nadmiernemu rozrastaniu się tabeli
    */
   
   private function DeleteOldKeys()
   {
      $this->db->query("DELETE FROM `__temporary_keys` WHERE `created` < '%1'", time() - 300);
   }
}
?>
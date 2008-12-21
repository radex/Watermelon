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

class Model_User extends Model
{
   public function Model_User()
   {
      parent::Model();
   }
   
   /*
    * public DBresult LoginUserData(string $login)
    * 
    * zwraca dane użytkownika niezbędne do zalogowania (hasło, algorytm
    * haszujący, sól, id)
    * 
    * string $login - nick usera, którego dane mają być zwrócone
    */
   
   public function LoginUserData($login)
   {
      $login = mysql_real_escape_string($login);

      return $this->db->query("SELECT `password`, `hashalgo`, `salt`, `id` FROM `__users` ".
                              "WHERE `nick` = '%1'", $login);
   }
   
   /*
    * public DBresult UpdatePassword(string $nick, string $hash, string $salt, int $hashAlgo)
    * 
    * aktualizuje hasło użytkownika
    * 
    * string $nick     - nick użytkownika, którego hasło ma zostać zaktualizowane
    * string $hash     - hash hasła
    * string $salt     - sól
    * int    $hashAlgo - numer algorytmu haszującego
    */
   
   public function UpdatePassword($nick, $hash, $salt, $hashAlgo)
   {
      return $this->db->query("UPDATE  `__users` SET " .
                              "`password` = '%1', `hashalgo` = '%2', `salt` = '%3' " .
                              "WHERE `nick` = '%4'", $hash, $hashAlgo, $salt, $nick);
   }
   
   /*
    * public bool UserExists(string $nick)
    * 
    * sprawdza, czy dany użytkownik istnieje
    * zwraca true, jeśli istnieje, false jeśli nie
    * 
    * string $nick - nick użytkownika, którego istnienie ma zostać sprawdzone
    */
   
   public function UserExists($nick)
   {
      $user = $this->db->query("SELECT `id` FROM `__users` WHERE `nick` = '%1'", $nick);
      $user = $user->num_rows();
      return ($user == 0 ? FALSE : TRUE);
   }
}

?>
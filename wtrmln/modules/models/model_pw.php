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

class Model_PW extends Model
{
   public function Model_PW()
   {
      parent::Model();
   }
   
   /*
    * public DBresult GetPWList(uint $user)
    *
    * Zwraca listę prywatnych wiadomości dla usera o UID = $user
    */

   public function GetPWList($user)
   { 
      $user = intval($user);

      return $this->db->query("SELECT `__private_messages`.* , `__users`.`nick` FROM `__private_messages` JOIN `__users` ON `__private_messages`.`from` = `__users`.`id` WHERE `to` = '%1' ORDER BY `id` DESC", $user);
   }
   
   /*
    * public DBresult GetPWData(uint $pw_id)
    * 
    * Zwraca dane prywatnej wiadomości o ID = $pw_id wraz z nickiem autora tej prywatnej wiadomości
    */
   
   public function GetPWData($pw_id)
   {
      $pw_id = intval($pw_id);
      
      return $this->db->query("SELECT `__private_messages`.*, `__users`.`nick` FROM `__private_messages` JOIN `__users` ON `__private_messages`.`from` = `__users`.`id` WHERE `__private_messages`.`id` = '%1'", $pw_id);
   }
   
   /*
    * public void SendPW(uint $author, uint $addressee, string $subject, string $text, uint $sent)
    * 
    * Wysyłanie prywatnej wiadomości
    * 
    * uint   $author    - UID autora prywatnej wiadomości
    * uint   $addressee - UID adresata
    * string $subject   - temat
    * string $text      - treść
    * uint   $sent      - timestamp wysłania wiadomości
    */
   
   public function SendPW($author, $addressee, $subject, $text, $sent)
   {
      // preparujemy zmienne
      
      $author    = intval($author);
      $addressee = intval($addressee);
      $subject   = mysql_real_escape_string($subject);
      $text      = mysql_real_escape_string($text);
      
      $subject   = htmlspecialchars($subject);
      $text      = htmlspecialchars($text);
      
      // wysyłamy
      
      $this->db->query("INSERT INTO `__private_messages` (`from`, `to`, `subject`, `text`, `sent`) VALUES ('%1', '%2', '%3', '%4', '%5')", $author, $addressee, $subject, $text, $sent);
   }
   
   /*
    * public uint GetPWAddressee(uint $pw_id)
    * 
    * Zwraca UID adresata prywatnej wiadomości o ID = $pw_id
    */
   
   public function GetPWAddressee($pw_id)
   {
      $pw_id = intval($pw_id);
      
      return $this->db->query("SELECT `to` FROM `__private_messages` WHERE `id` = '%1'", $pw_id)->to_obj()->to;
   }
   
   /*
    * public DBresult GetAddresseeUID(string $nick)
    * 
    * Zwraca UID adresata o nicku $nick
    */
   
   //TODO: to trza koniecznie do model_user "przetransportować"
   
   public function GetAddresseeUID($nick)
   {
      $nick = mysql_real_escape_string($nick);
      
      return $this->db->query("SELECT `id` FROM `__users` WHERE `nick` = '%1'", $nick);
   }
   
   /*
    * public void Delete(uint $id)
    * 
    * Usuwa prywatną wiadomość o ID = $id
    */
   
   public function Delete($id)
   {
      $id = intval($id);
      $this->db->query("DELETE FROM `__private_messages` WHERE `id` = '%1'", $id);
   }
   
   public function SetReaded($id)
   {
      $id = intval($id);
      $this->db->query("UPDATE `__private_messages` SET `readed` = 1 WHERE `id` = '%1'", $id);
   }
}
?>